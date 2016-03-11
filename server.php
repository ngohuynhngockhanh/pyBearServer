<?php
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
} else {
    echo "<h1>Please install via composer.json</h1>";
    echo "<p>Install Composer instructions: <a href='https://getcomposer.org/doc/00-intro.md#globally'>https://getcomposer.org/doc/00-intro.md#globally</a></p>";
    echo "<p>Once composer is installed navigate to the working directory in your terminal/command promt and enter 'composer install'</p>";
    exit;
}

if (!is_readable('app/Core/Config.php')) {
    die('No Config.php found, configure and rename Config.example.php to Config.php in app/Core.');
}

use PHPSocketIO\SocketIO;
use PHPSocketIO\Connection;
use PHPSocketIO\Response\Response;
use PHPSocketIO\Event;
$socketio = new SocketIO();
$chat = $socketio
        ->getSockets()
        ->on('addme', function(Event\MessageEvent $messageEvent) use (&$chat) {
            $messageEvent->getConnection()->emit(
                    'update',
                    array('msg' => "Welcome {$messageEvent->getMessage()}")
            );
            $chat->emit('update', array('msg' => "{$messageEvent->getMessage()} is coming."));
        })
        ->on('msg', function(Event\MessageEvent $messageEvent) use (&$chat) {
            $message = $messageEvent->getMessage();
            $chat->emit('update', $message);
        });
$socketio
        ->listen(8080)
        ->onConnect(function(Connection $connection) use ($socketio) {
            list($host, $port) = $connection->getRemote();
            echo "connected $host:$port\n";
        })
        ->onRequest('/', function($connection, \EventHttpRequest $request) {
                $response = new Response(file_get_contents(__DIR__.'/web/index.html'));
                $response->setContentType('text/html', 'UTF-8');
                $connection->sendResponse($response);
        })
        ->onRequest('/socket.io.js', function($connection, \EventHttpRequest $request) {
                $response = new Response(file_get_contents(__DIR__.'/web/socket.io.js'));
                $response->setContentType('text/html', 'UTF-8');
                $connection->sendResponse($response);
        })
        ->dispatch();