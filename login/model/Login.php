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
		public function login(Anonymous $toBeLoggedIn) {
			
			 if( $this->authenticate($toBeLoggedIn) ) {
			 	$this->storeUserInSession($toBeLoggedIn->getUsername());
			 	$this->storeTemporaryPassword( $toBeLoggedIn->getUsername(), $this->generateTemporaryPassword($toBeLoggedIn->getUsername()) );
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
		public function getTemporaryPassword($username ,$password) {
			$this->storeTemporaryPassword( $username, $this->generateTemporaryPassword($password) );
			return 	$this->temporaryPassword;
		
		}


		/**
		* Authenticates a user
		* @return boolean
		*/
		private function authenticate(Anonymous $toBeLoggedIn) {
			

			$user = $this->UserCollection->getUser($toBeLoggedIn->getUsername());

			if( $user ) {

				$login = password_verify(  $toBeLoggedIn->getPassword(), $user->getPassword());

				if($login) {
					return true;
				}
				else {
					$username = $toBeLoggedIn->getUsername();
					$stmt = $this->UserCollection->db->db->prepare("SELECT temp_password FROM users WHERE username = :username");
					$stmt->bindParam(':username', $username);
					$stmt -> execute();

					$temporaryPassword = $stmt->fetch();
					$temporaryPassword = $temporaryPassword[0];
					if ($temporaryPassword == $toBeLoggedIn->getPassword()) {
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