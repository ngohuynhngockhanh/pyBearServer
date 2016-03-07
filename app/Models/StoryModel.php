<?php
namespace Models;

use Core\Model;

class StoryModel extends Model {
	public function getList($limit = 10, $from = 0) {
		return $this->db->select("SELECT * FROM ".PREFIX."story LIMIT :from, :limit", array(':from' => $from, ":limit" => $limit));
	}
}