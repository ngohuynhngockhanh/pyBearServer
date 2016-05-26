<?php
namespace Controllers;

use Core\View;
use Core\Controller;
use Models\StoryModel;
use Models\RestAPI;
use Models\Config;
use Models\User;
use Models\SocketClient;
use Helpers\AILab;

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
	private $config;
    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
		$this->storyModel = new StoryModel();
		$this->RestAPI = new RestAPI();
		$this->config = new Config();
    }

    /**
     * List all story from variable $_GET
     */
    public function getList()
    {
		$uid = intval($_GET['uid']);
		$data = $this->storyModel->getList($uid);
		$this->RestAPI->display($data);
    }
	
	
	/**
	 * Update all list
	 */
	public function updateList() {
		$uid = intval($_GET['uid']);
		$timestamp = intval($this->config->get('timestamp'.$uid));		
		if ($timestamp < 1)
			$timestamp = 1;
		
		$AILab = new AILab($timestamp);
		$resp = $AILab->getall($uid);
		if ($resp->items) {
			$resp->items = (array)$resp->items;
			if ($resp->items)
				foreach ($resp->items as $i => $item) {
					$item = (array) $item;
					$item['uid'] = $resp->uid;
					$this->storyModel->insert($item);
				}
		}
		$this->config->set("timestamp".$uid, $resp->timestamp);
		$this->RestAPI->display($this->config->get('timestamp'.$uid));
	}
	
	/**
	* play a story
	*/
	public function play() {
		$uid = intval($_GET['uid']);
		$user = User::getInstance()->getUserFromUID($uid);
		if ($user == null)
			return false;
		$roomID = $user->get('roomID');
		$url = $this->RestAPI->parseInput()->url;
		$sid = intval($this->RestAPI->parseInput()->sid);
		if ($this->RestAPI->getMethod() == "POST") {
			SocketClient::emit('playFromURL', [
				'url' 		=> $url,
				'roomID' 	=> $roomID,
				'sid'		=> $sid,
				'uid'		=> $uid,
			]);
		}
		
		$this->RestAPI->display(array(
			'url'	=>	$url
		));
	}
	
	/*
	*	update playlist
	*/
	public function playlistUpdate() {
		$uid = intval($_GET['uid']);
		$user = User::getInstance()->getUserFromUID($uid);
		$roomID = $user->get('roomID');
		
		$playlist = $this->RestAPI->parseInput()->playlist;
		User::getInstance()->updatePlaylist($uid, $playlist);
		
		SocketClient::emit('playlist', $this->storyModel->getListOfStoryFromPlaylist($uid, $roomID));
		
		$this->RestAPI->display("ok");
	}
	
	/**
	* triggerText
	*/
	public function triggerUrl() {
		$uid = intval($_GET['uid']);
		$url = $_GET['url'];
		
		$this->RestAPI->display(User::getInstance()->updateTriggerUrl($uid, $url) ? "ok" : "error not found");
	}
	
	/*
	* Set volume
	*/
	public function setVolume() {
		$uid = intval($_GET['uid']);
		$user = User::getInstance()->getUserFromUID($uid);
		if ($user == null)
			return false;
		$roomID = $user->get('roomID');
		$volume = $this->RestAPI->parseInput()->volume;
		SocketClient::emit('setVolume', [
			'volume' 	=> $volume,
			'roomID' 	=> $roomID,
			'uid'		=> $uid,
		]);
		
		$this->RestAPI->display("ok");
	}
}
