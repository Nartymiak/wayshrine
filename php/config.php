<?php

	$config = new Config();

	class Config {
		private $serverName;
		private $key;

		public function __construct() {
			$this->serverName = $_SERVER['SERVER_NAME'];
			$this->key = 'spiff';
		}
		
		public function getServerName(){
			return $this->serverName;
		}

		public function getKey(){
			return $this->key;
		}

	}
?>