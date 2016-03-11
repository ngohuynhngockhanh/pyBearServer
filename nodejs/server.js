var app = require('http').createServer(handler)
var io = require('socket.io')(app);
var fs = require('fs');

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

io.on('connection', function (socket) {
	socket.emit('play', {url: 'http://www.ailab.hcmus.edu.vn/voice_adaption/uploads/ngan.mp3'});
	setTimeout(function() {
		socket.emit('play', {url: 'http://www.noiseaddicts.com/samples_1w72b820/280.mp3'});
	}, 10000);
	socket.emit('news', { hello: 'world' });
	socket.on('my other event', function (data) {
		console.log(data);
	});
});