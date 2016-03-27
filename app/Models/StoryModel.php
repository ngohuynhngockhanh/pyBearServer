<?php
namespace Models;

use Core\ModelMongo;
use Models\User;

class StoryModel extends ModelMongo {
	public function getList($uid) {
		$playlist = User::getInstance()->getPlaylist($uid);
		$playlistByKey = [];
		if ($playlist) {
			foreach ($playlist as $i => $sid) {
				$playlistByKey[$sid] = true;
			}
		}
		
		$collection = $this->mongo->getCollection('story');
		$cursor = $collection->find()->where('uid', $uid);
		$documentList = $cursor->findAll();
		$result = array();
		foreach ($documentList as $_id => $document) {
			$res = array(
				'sid'		=> $document->id,
				'title'		=> $document->title,
				'default_url' => $document->link
			);
			if (isset($playlistByKey[$document->id]))
				$res['checked'] = true;
			$result[] = $res;
		}
		return $result;
		//return $this->db->select("SELECT * FROM ".PREFIX."story LIMIT :from, :limit", array(':from' => $from, ":limit" => $limit));
	}
	
	public function insert($item) {
		$doc = $this->mongo->getCollection('story')->find()->where('id', $item['id'])->where('uid', $item['uid'])->findOne();
		if (!$doc)
			$this->mongo->getCollection('story')->createDocument($item)->save();
		else { 
			$doc->update($item)->save();
		}
	}
}