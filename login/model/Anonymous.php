<?php

namespace model;

	class Anonymous {

		/* The User who want to be logged in.
		 * This class holds the user credentials submitted by the still anonymous user
		 *
		 */

		private $username;
		private $password;


		/**
		* Creates a new user
		* @param $username string
		* @param $password string
		* @return null
		*/
		public function __construct($username, $password) {
			
			assert(isset($username) && isset($password));
			$this->username = $username;
			$this->password = $password;

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