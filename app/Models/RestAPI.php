<?php
namespace Models;

use Core\Model;
use Core\View;

class RestAPI extends Model {
	private $_outputType 		= 'json';
	private $_respsonseCode 		= 1;
	private $_errorMesg			= "";
	private $_input				= null;
	
	public function __construct($outputType = 'json') {
		$this->_outputType = $outputType;
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
			'respsonseCode' => $this->_respsonseCode,
			'errorMesg' => $this->_errorMesg,
			'data'			=>	$data
		);
		return $resp;
	}
	
	//return output
	public function output($data) {
		$resp = $this->generateResp($data);
		$res = "";
		switch ($this->_outputType) {
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
	
	//get input from json
	private function _getInput() {
		if (!$this->_input) {
			$this->_input = file_get_contents("php://input");
		}
		return $this->_input;
	}
	
	//parse Input
	public function parseInput() {
		$this->_getInput();
		return json_decode($this->_input);
		
	}
	
	//get method from post
	public function getMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}
	
	//display
	public function display($data) {
		View::render('restAPI', $this->output($data));
	}
}