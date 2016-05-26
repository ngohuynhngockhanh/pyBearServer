<?php
namespace Models;

use Core\ModelMongo;

class User extends ModelMongo {
	public static $instance;
	public static function getInstance() {
		if (!isset(self::$instance))
			self::$instance = new User();
		return self::$instance;
	}
	public function getUserFromUID($uid) {
		$uid = intval($uid);
		return $this->mongo
					->getCollection('user')
					->find()
					->where('uid', $uid)
					->findOne();
	}
	public function updatePlaylist($uid, $list) {
		//[['sid' => 123]]
		$user = $this->getUserFromUID($uid);
		if ($user) {
			$user->set('playlist', $list)->save();
			return true;
		}
		return false;
	}
	
	public function getPlaylist($uid) {
		return $this->getUserFromUID($uid)->get("playlist");
	}
	
	public function updateTriggerUrl($uid, $url) {
		$user = $this->getUserFromUID($uid);
		if ($user) {
			$user->set('processedUrl', $url)->save();
			return true;
		}
		return false;
	}
}