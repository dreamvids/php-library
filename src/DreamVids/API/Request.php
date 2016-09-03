<?php
namespace DreamVids\API;

class Request {
	const API_URL = 'http://dev.box:8080/shared/DreamVids/API/';

	private $curl;
	private $rawResponse;
	private $response;
	private $headers;

	public function __construct($sessid = null, $method = 'GET', $uri = '', $data = [], $json = true) {
		$contentType = ($json) ? 'application/json' : 'application/x-www-form-urlencoded';
		$this->rawResponse = '';
		$this->response = [];
		$dataToHash = json_encode([
			Client::$NAME,
			Client::$DOMAIN,
			$uri,
			$method,
			$data
		]);
		$this->headers = [
			'X-Public: ' . Client::$PUBLIC_KEY,
			'X-Hash: ' . hash_hmac('sha512', $dataToHash, Client::$PRIVATE_KEY)
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