<?php
namespace Models;

use Core\ModelMongo;

class StoryModel extends ModelMongo {
	public function getList($limit = 10, $from = 0) {
		$collection = $this->mongo->getCollection('story');
		$cursor = $collection->find();
		$documentList = $cursor->findAll();
		$result = array();
		foreach ($documentList as $_id => $document) {
			$result[] = array(
				'sid'		=> $document->id,
				'title'		=> $document->title,
				'default_url' => $document->link
			);
		}
		return $result;
		//return $this->db->select("SELECT * FROM ".PREFIX."story LIMIT :from, :limit", array(':from' => $from, ":limit" => $limit));
	}
	
	public function insert($item) {
		$doc = $this->mongo->getCollection('story')->find()->where('id', $item['id'])->findOne();
		if (!doc)
			$this->mongo->getCollection('story')->createDocument($item)->save();
		else 
			$doc->update($item)->save();
	}
}