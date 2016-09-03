<?php
namespace DreamVids\API;

class Client {
	private $sessid;

	public function __construct($sessid = null) {
		$this->sessid = $sessid;
	}

	public function getSessid() {
		return $this->sessid;
	}

	public function setSessid($sessid) {
		$this->sessid = $sessid;
	}

	public function prepare($method, $uri, $data = [], $json = true) {
		return new Request($this->sessid, $method, $uri, $data, $json);
	}
}