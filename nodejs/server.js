var app = require('http').createServer(handler)
var io = require('socket.io')(app);
var bear = io.of('/bear');
var phpServer = io.of('/phpServer');
var fs = require('fs');
var phpjs = require('phpjs');

app.listen(1234);

function handler (req, res) {
  fs.readFile(__dirname + '/index.html',
  function (err, data) {
    if (err) {
      res.writeHead(500);
      return res.end('Error loading index.html');
    }

    res.writeHead(200);
    res.end(data);
  });
}

//play emit
function play(socket, url) {
	var data = {url: url};
	socket.emit('play', data);
}

bear.on('connection', function (socket) {
	console.log("bear connected");
	socket.on('joinRoom', function(data) {
		socket.join(data['roomID']);
		console.log("join room " + data['roomID']);
	});
});

phpServer.on('connection', function (socket) {
	console.log("php connected");
	socket.on('playFromURL', function(data) {
		console.log(data);
		if (phpjs.isset(data['url'])) {
			var url = data['url'];
			var roomID = data['roomID'];
			var sid = data['sid'];
			var uid = data['uid'];
			if (roomID == undefined)
				roomID = "_________________________________";
			console.log(url);
			console.log(roomID);
			bear.to(roomID).emit('play', {
				url: url,
				sid: sid,
				uid: uid
			});
		}
	});
	
	
	socket.on('playlist', function(data) {
		var roomID = data['roomID'];
		bear.to(roomID).emit('updatePlaylist', data);
	});
	
	socket.on('setVolume', function(data) {
		var roomID = data['roomID'];
		var volume = data['volume'];
		bear.to(roomID).emit('setVolume', data);
	});
});