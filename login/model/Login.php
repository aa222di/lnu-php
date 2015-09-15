<?php

namespace model; 

	class Login {

		private $UserCollection;
		private static $userInSession = "Login::userInSession";
	

		public function __construct( UserCollection $UserCollection ) {

			$this->UserCollection = $UserCollection;

		}


		/**
		* Login a user
		* @return boolean
		*/
		public function login($username, $password) {
			// Try to authenticate user
			$loggedIn = $this->authenticate($username, $password);
			
			// If user exists and is authenticated, store user in $_SESSION
			if ($loggedIn) {
				$this->storeUserInSession($username);
			}

			return true;
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
		* Authenticates a user
		* @return boolean
		*/
		private function authenticate($username, $password) {
			$user = $this->UserCollection->getUser($username);
			
			if($user) {
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
			
			if (session_status() == PHP_SESSION_NONE) {
				echo "Hej";
    			session_start();
			}

			$_SESSION[self::$userInSession] = $username;
		}


	}