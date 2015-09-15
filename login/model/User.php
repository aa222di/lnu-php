<?php

namespace model;

	class User {

		private $username;
		private $password;

		/**
		* Creates a new user
		* @param $username string
		* @param $password string
		* @return null
		*/
		public function __construct($username, $password) {
		
			if (!isset($username) || !isset($password)) {
				throw new \Exception("Both password and username has to be set to create a new user");
			}

			$this->username = $username;
			$this->password = password_hash($password, PASSWORD_DEFAULT);
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