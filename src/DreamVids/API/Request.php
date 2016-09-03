<?php
namespace DreamVids\API;

class Request {
	const API_URL = 'http://dev.box:8080/shared/DreamVids/API/';
	const CLIENT_PUBLIC_KEY = '9b55842ccdb4aae14fda2b22098eb9e682cb2b2c9f74d638f8292b4956af583eafb6e5567d5b1192e91ce37c08cae496d4c18fdcf8f411b304ddd8593a892475';
	const CLIENT_PRIVATE_KEY = '7746c0d84f7448c8fccc2104ee927d56c39825413296ebd30a926bcdbc70b9f2bf7081b13acdcdfc52f95019a155192d5a90d033f099f63acd207a4c1fe64852';
	const CLIENT_NAME = 'root';
	const CLIENT_DOMAIN = 'localhost';

	private $curl;
	private $rawResponse;
	private $response;
	private $headers;

	public function __construct($sessid = null, $method = 'GET', $uri = '', $data = [], $json = true) {
		$contentType = ($json) ? 'application/json' : 'application/x-www-form-urlencoded';
		$this->rawResponse = '';
		$this->response = [];
		$dataToHash = json_encode([
			self::CLIENT_NAME,
			self::CLIENT_DOMAIN,
			$uri,
			$method,
			$data
		]);
		$this->headers = [
			'X-Public: ' . self::CLIENT_PUBLIC_KEY,
			'X-Hash: ' . hash_hmac('sha512', $dataToHash, self::CLIENT_PRIVATE_KEY)
		];
		if ($sessid != null)
			$this->headers[] = 'X-Session-ID: ' . $sessid;
		$this->curl = curl_init(self::API_URL . $uri);
		if ($method != 'GET') {
			$this->headers[] = 'Content-Type: ' . $contentType;
			curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
			$data = ($json) ? json_encode($data) : http_build_query($data);
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data); 
		}
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
	}

	public function addHeader($value) {
		$this->headers[] = $value;
	}

	public function send() {
		curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
		$this->rawResponse = curl_exec($this->curl);
		$this->response = json_decode($this->rawResponse);
	}

	public function getRawResponse() {
		return $this->rawResponse;
	}

	public function getResponse() {
		return $this->response;
	}

	public function getResponseCode() {
		if (isset($this->response->code))
			return $this->respone->code;
	}

	public function getResponseData() {
		if (isset($this->response->data))
			return $this->response->data;
	}

	public function getResponseErrors() {
		if(isset($this->response->errors))
			return $this->reponse->errors;
	}
}