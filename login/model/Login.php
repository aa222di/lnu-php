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
			// Try to authenticate user
			if(!$username) {
				throw new \Exception("Username is missing");
			}

			elseif(!$password) {
				throw new \Exception("Password is missing");
			}

			elseif ($this->checkLoginStatus()) {
				return true;
			}

			else {
				$loggedIn = $this->authenticate($username, $password);
				
				// If user exists and is authenticated, store user in $_SESSION
				if ($loggedIn) {
					$this->storeUserInSession($username);
					return "Welcome";
				}

				else {
					throw new \Exception("Wrong name or password");
				}
			}
			
			return true;
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

			$_SESSION[self::$userInSession] = $username;
		}


	}