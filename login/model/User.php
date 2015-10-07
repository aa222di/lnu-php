<?php

namespace model;

	class User {

		private $username;
		private $password;
		private $temp_password;

		/**
		* Creates a new user
		* @param $username string
		* @param $password string
		* @return null
		*/
		public function __construct($username = null, $password = null) {


			// Objects of this class are both created from PDO::FETCH_CLASS and manually
			// therefore it is necessarry to check how to set the member variables.
			if(!isset($this->username) && !isset($this->username)) {
				assert(isset($username) && isset($password));
				$this->username = $username;
				$this->password = password_hash($password, PASSWORD_DEFAULT);
			}
		
			
		}

		/**
		* Get username
		* @return string
		*/
		public function getUsername() {
			return $this->username;
		}

		/**
		* Get password
		* @return string
		*/
		public function getPassword() {
			return $this->password;
		}

	}