<?php
namespace DreamVids\API;

class Client {
	public static $PUBLIC_KEY = '';
	public static $PRIVATE_KEY = '';
	public static $NAME = '';
	public static $DOMAIN = '';

	private $sessid;

	public function __construct($public_key = '', $private_key = '', $name = '', $domain = '') {
		self::$PUBLIC_KEY = $public_key;
		self::$PRIVATE_KEY = $private_key;
		self::$NAME = $name;
		self::$DOMAIN = $domain;
		$this->sessid = null;
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