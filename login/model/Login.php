<?php

namespace model; 

	class Login {

		private $UserCollection;
		private $temporaryPassword;
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
			 	$this->storeTemporaryPassword( $username, $this->generateTemporaryPassword($password) );
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

			return 	$this->temporaryPassword;
		
		}


		/**
		* Authenticates a user
		* @return boolean
		*/
		private function authenticate($username, $password) {
			$user = $this->UserCollection->getUser($username);
			
			if( $user ) {
				$login = password_verify($password, $user->getPassword());

				if($login) {
					return true;
				}
				else {

					$stmt = $this->UserCollection->db->db->prepare("SELECT temp_password FROM users WHERE username = :username");
					$stmt->bindParam(':username', $username);
					$stmt -> execute();

					$temporaryPassword = $stmt->fetch();
					$temporaryPassword = $temporaryPassword[0];
					if ($temporaryPassword == $password) {
						return true;
					}
				}
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

		/**
		* Stores  temporary password
		* 
		*/
		private function storeTemporaryPassword( $username ,$password ) {

			$stmt = $this->UserCollection->db->db->prepare("UPDATE users SET temp_password = :password WHERE username = :username");

				$stmt->bindParam(':username', $username);
				$stmt->bindParam(':password', $password);
				$stmt -> execute();
		}

		/**
		* Generates a temporary password
		* 
		*/
		private function generateTemporaryPassword($password) {

			$this->temporaryPassword = password_hash($password, PASSWORD_DEFAULT);
			return 	$this->temporaryPassword;
		}


	}