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
	
	public function getListOfStoryFromPlaylist($uid, $roomID) {
		$playlist = User::getInstance()->getPlaylist($uid);
		$collection = $this->mongo->getCollection('story');
		$orExpression = [];
		for ($i = 0; $i < count($playlist); $i++)
			$orExpression[$i] = $collection->expression()->where('id', $playlist[$i]);
		
		$cursor = $collection->find()->where('uid', $uid)
									->whereOr($orExpression);
									
		$documentList = $cursor->findAll();
		$result = array();
		foreach ($documentList as $_id => $document) {
			$res = array(
				'sid'		=> $document->id,
				'title'		=> $document->title,
				'path' 		=> $document->link,
				'uid'		=> $uid
			);
			$result[] = $res;
		}
		$result = ['roomID' => $roomID, 'playlist' => $result, 'uid' => $uid];
		return $result;
	}
}