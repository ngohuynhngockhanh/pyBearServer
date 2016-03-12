<?php
namespace Models;

use Core\Model;

class RestAPI extends Model {
	private $outputType 		= 'json';
	private $respsonseCode 		= 1;
	private $errorMesg			= "";
	
	public function __construct($outputType = 'json') {
		$this->outputType = $outputType;
	}
	
	//output json 
	public function outputJSON($resp) {
		return json_encode($resp);
	}
	
	//output xml
	public function outputXML($resp) {
		return $resp;
	}
	
	//output jsonp
	public function outputJSONP($resp) {
		return $resp;
	}
	
	//generate respsonse
	public function generateResp($data) {
		$resp = array(
			'respsonseCode' => $this->respsonseCode,
			'errorMesg' => $this->errorMesg,
			'data'			=>	$data
		);
		return $resp;
	}
	
	//return output
	public function output($data) {
		$resp = $this->generateResp($data);
		$res = "";
		switch ($this->outputType) {
			case "xml":
				$res = $this->outputXML($resp);
				break;
			case "jsonp":
				$res = $this->outputJSONP($resp);
				break;
			default:
				$res = $this->outputJSON($resp);
				break;
		}
		return $res;
	}
}