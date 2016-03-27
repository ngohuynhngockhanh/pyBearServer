<?php
namespace Helpers;

use Curl\Curl;

class AILab {
	
	protected	$hostAddress = "http://cotich.itec.edu.vn/api/";
	protected 	$timestamp;
	
	protected function createUrl($path) {
		return $this->hostAddress . $path;
	}
	
	/*
	 * post to $url with data
	 */
	protected function post($url, $data) {
		$curl = new Curl();
		$data['timestamp'] = $this->timestamp;
		$curl->post($url, $data);
		return $curl;
	}
	
	public function __construct($timestamp = 1) {
		$this->timestamp = $timestamp;
	}
	
	
	public function getall($uid = 1) {
		$url = $this->createUrl("customize/getall/");
		$curl = $this->post($url, ['uid' => $uid]);
		$data = $curl->response;
		$curl->close();
		$data = @json_decode($data);
		if ($data && !$data->uid) {
			$data->uid = $uid;
		}
		return $data;
	}
	
	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}
}