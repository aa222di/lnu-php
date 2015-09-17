<?php

namespace model; 

	class Login {

		private $UserCollection;
		private static $userInSession = "Login::userInSession";
	

		public function __construct( UserCollection $UserCollection ) {
			$this->UserCollection = $UserCollection;
			//make sure we have a session
			assert(isset($_SESSION));
		}


		/**
		* Login a user
		* @return boolean
		*/
		public function login($username, $password) {
			
			 if( $this->authenticate($username, $password) ) {
			 	$this->storeUserInSession($username);
			 	return true;
			 }

			 return false;
				
		}

		/**
		* Logs out a user
		* @return void
		*/
		public function logout() {
			if(isset($_SESSION[self::$userInSession])) {
				unset($_SESSION[self::$userInSession]);
				return "Bye bye!";
			}
		
			return;
		}

		/**
		* Checks if user is logged in
		* @return boolean
		*/
		public function checkLoginStatus() {

			if(isset($_SESSION[self::$userInSession])) {
				return true;
			}

			return false;
		}

		/**
		* Generates temporary password
		* @return string
		*/
		public function getTemporaryPassword($password) {

			// generate and store pwd

			// return
			return password_hash($password, PASSWORD_DEFAULT);
		
		}


		/**
		* Authenticates a user
		* @return boolean
		*/
		private function authenticate($username, $password) {
			$user = $this->UserCollection->getUser($username);
			
			if( $user ) {
				return password_verify($password, $user->getPassword());
			}
			else {
				return false;
			}
		}

		/**
		* Stores user in session
		* 
		*/
		private function storeUserInSession($username) {

			$_SESSION[self::$userInSession] = $username;
		}


	}