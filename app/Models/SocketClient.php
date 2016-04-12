<?php
namespace Models;

use ElephantIO\Client,
    ElephantIO\Engine\SocketIO\Version1X;
	
use Core\ModelMongo;

class SocketClient extends ModelMongo {
	public static function emit($message, $value) {
		$client = new Client(new Version1X('http://127.0.0.1:1234'));
		$client->initialize();
		$client->of('/phpServer');
		$client->emit($message, $value);
		$client->close();	
	}
}