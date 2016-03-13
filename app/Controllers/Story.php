<?php
namespace Controllers;

use Core\View;
use Core\Controller;
use Models\StoryModel;
use Models\RestAPI;
use ElephantIO\Client,
    ElephantIO\Engine\SocketIO\Version1X;

/*
 * Story controller
 *
 * @author Ngoc Khanh Ngo Huynh - nhnkhanh@arduino.vn - http://arduino.vn/ksp
 * @version 1.0
 * @date March 07, 2016
 * @date updated March 07, 2016
 */
class Story extends Controller
{
	private $storyModel;
	private $RestAPI;
    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
		$this->storyModel = new StoryModel();
		$this->RestAPI = new RestAPI();
    }

    /**
     * List all story from variable $_GET
     */
    public function getList()
    {
		$data = $this->storyModel->getList();
		$this->RestAPI->display($data);
    }
	
	
	/**
	* play a story
	*/
	public function play() {
		$roomID = $_GET['roomID'];
		$url = $this->RestAPI->parseInput()->url;
		
		if ($this->RestAPI->getMethod() == "POST") {
			$client = new Client(new Version1X('http://127.0.0.1:1234'));
			$client->initialize();
			$client->of('/phpServer');
			$client->emit('playFromURL', ['url' => $url, 'roomID' => $roomID]);
			$client->close();	
		}
		
		$this->RestAPI->display(array(
			'url'	=>	$url
		));
	}
	
	
}
