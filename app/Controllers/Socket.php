<?php
namespace Controllers;

use Core\Controller;
use React\EventLoop\Factory;
use React\Socket\Server;
/*
 * Socket controller
 *
 * @author Ngoc Khanh Ngo Huynh - nhnkhanh@arduino.vn - http://arduino.vn/ksp
 * @version 1.0
 * @date March 11, 2016
 * @date updated March 11, 2016
 */
class Socket extends Controller
{
    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * create socket server
     */
    public function createServer()
    {
		$loop = Factory::create();
		
		$socket = new Server($loop);
		$socket->on('connection', function ($conn) {
			$conn->write("Hello there!\n");
			$conn->write("Welcome to this amazing server!\n");
			$conn->write("Here's a tip: don't say anything.\n");

			$conn->on('data', function ($data) use ($conn) {
				$conn->close();
			});
		});
		$socket->listen(1337);

		$loop->run();
    }

}
