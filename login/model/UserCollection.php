<?php

namespace model; 

	class UserCollection {

		private $users = [];

		public function __construct() {

		}

		/**
		* Creates new User and adds it to collection
		* @param $username string
		* @param $password string
		* @return void 
		*/
		public function createNewUser($username, $password) {

			if (!isset($username) || !isset($password)) {
				throw new \Exception("Both password and username has to be set to create a new user");
			}

			$this->add(new User($username, $password));

		}

		/**
		* @return obj User
		*/
		public function getUser($username) {
			if (isset($this->users[$username])) {
				return $this->users[$username];
			}
		}

		/**
		* @return array 
		*/
		public function getUserCollection() {
			return $this->users;
		}

		/**
		* Authenticates a user
		* @return boolean
		*/
		public function authenticate($username, $password) {
			$user = $this->getUser($username);
			
			if($user) {
				return password_verify($password, $user->getPassword());
			}
			else {
				return false;
			}
		}

		/**
		* Adds new user to collection
		*/
		private function add( User $userToAdd) {
			$this->users[$userToAdd->getUsername()] = $userToAdd;
		}

		
	}