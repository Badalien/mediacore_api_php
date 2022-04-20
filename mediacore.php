<?php
require_once('functions.php');

class MediaCore
{
	private $config_array;
	private $session_id;
	private $zUserId;
	
	public function __construct($zUserId = NULL)
	{
		$this->config_array = parse_ini_file("config.ini", true);
		$this->session_id = $this->login();
		$this->zUserId = $zUserId;
	}
	
	
	// used to get any data from mediacore server, pass array data and string method
	public function call($data, $method, $sendSessionId = true, $includeData = true) 
	{
		$start = microtime(TRUE);
		if ($includeData) {
			$data = ['data' => $data];
		}
		
		if ($sendSessionId) {
			$data = ['session_id' => $this->session_id] + $data;
		}
		$payload = json_encode($data, true);
		echo $this->config_array['main']['url'].$method . "\n";
		echo $payload . "\n";
		$header = array('Expect:  ');
		if ($curl = curl_init()) 
		{
			curl_setopt($curl, CURLOPT_URL, $this->config_array['main']['url'].$method);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$answer = json_decode(curl_exec($curl), true);
			curl_close($curl);


			$end = microtime(TRUE);
			echo "The code took " . ($end - $start) . " seconds to complete.\n\n";
			// having 'data' and 'fields' fields means that request was successful
			// for such requests lets use nice return function
			if (isset($answer['data']) && isset($answer['fields'])) {
				return $this->niceReturn($answer);
			} else {
				return $answer;
			}
			print_json($answer);
		} else {
			echo "CURL init Failed\n\n";
		}
	}

	// Авторизация на сервере медиакора, получения session_id, он нужен для всех операций. Сохраняется в течение 2х часов бездействия
	//
	// Mediacore server authorization, 
	// returns session_id which is used for all other requests
	// valid until 2 hours idle
	private function login()
	{
		$data = array(
			'login' => $this->config_array['main']['login'],
			'password' => $this->config_array['main']['password'],
			'ip' => $this->config_array['main']['ip']
		);
		$result = $this->call($data, 'login', false, false);
		return $result['session_id'];
	}
	
	// Разрыв сесии
	//
	// session dropout
	// use it to stop raping mediacore server and let it go
	private function logout()
	{
		$data = array(
			'forced_logout' => true
		);
		$result = $this->call($data, 'logout');
		return $result;
	}
	
	// make default mediacore api responce acceptable again
	private function niceReturn($array)
	{
		if ($array['status'] != '200') {
			return $array;
		}
		$result = [];
		$dataArray = $array['data'];
		$keysArray = $array['fields'];
		foreach ($keysArray as $numero => $keyValue) {
			foreach ($dataArray as $key => $dataValue) {
				$result[$key][$keyValue] = $dataValue[$numero];
			}
		}
		return $result;
	}

	
	public function __destruct()
	{
		$this->logout();
	}
}