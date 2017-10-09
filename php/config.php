<?php

	$config = new Config();

	class Config {
		private $serverName;
		private $key;

		public function __construct() {
			$this->serverName = $_SERVER['SERVER_NAME'];
			$this->key = 'BzOyKLymiRJTXCxGpSit9dDyceM8Rlb5kuwWzy/HxiKqQS495RZO05QkeOzULEluKmbf0lcOrhKJ47XyM6m+AA==';
		}
		
		public function getServerName(){
			return $this->serverName;
		}

		public function getKey(){
			return $this->key;
		}

	}
?>