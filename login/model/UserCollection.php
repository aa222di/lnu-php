<?php

namespace model; 

	class UserCollection {

		private $users = [];
		private $registrationSucceeded;
		public $db;

		private static $LastRegisteredUser = "UserCollection::LastRegisteredUser";

		public function __construct( Database $db ) {
			
			// Connect to a database
			$this->db = $db;

			// Create table for users
			$sql = "CREATE TABLE IF NOT EXISTS `users` (
  									`username` VARCHAR(20) NOT NULL,
 									 `password` VARCHAR(120) NOT NULL,
 									 `temp_password` VARCHAR(120) NULL,
  									PRIMARY KEY (`username`));";
				try {
					$this->db->db->exec($sql);
				}
				catch(\PDOException $e) {
    				echo $e->getMessage();//Remove or change message in production code
    			}

    		$stmt = $this->db->db->prepare("SELECT username, password FROM users");
    		$stmt -> execute();
    		$this->users = $stmt->fetchAll(\PDO::FETCH_CLASS, '\model\User');
    		$this->registrationSucceeded = false;
		}

		/**
		* Creates new User and adds it to collection
		* @param $username string
		* @param $password string
		* @return void or boolean
		*/
		public function createNewUser(Anonymous $userToAdd) {
			$username =$userToAdd->getUsername();
			$password =$userToAdd->getPassword();
			assert(isset($username) && isset($password));
			if($this->add($userToAdd)) {
				$this->registrationSucceeded = true;
				$this->saveRegisteredUserInSession($userToAdd);
				return true;
			}

		}


		/**
		* @return obj User
		*/
		public function getUser($username) {

			foreach ($this->users as $key => $user) {
				if($username == $user->getUsername()) {
					return $user;
				}
			}
		}

		/**
		* @return array with \model\User objects
		*/
		public function getUserCollection() {
			return $this->users;
		}

		/**
		* @return boolean 
		*/
		public function getRegistrationSucceeded() {
			return $this->registrationSucceeded;
		}

		/**
		* @return string or void
		*/
		public static function getRegisteredUser() {
			if(isset($_SESSION[self::$LastRegisteredUser])) {
				$ret = $_SESSION[self::$LastRegisteredUser];
				unset($_SESSION[self::$LastRegisteredUser]);
				return $ret;
			}
			return;
		}

		/**
		* @return boolean 
		*/
		private function saveRegisteredUserInSession(Anonymous $userToSave) {
			$_SESSION[self::$LastRegisteredUser] = $userToSave->getUsername();
		}


		/**
		* Adds new user to collection
		* @return boolean
		*/
		private function add( Anonymous $userToAdd) {
			
			$username =$userToAdd->getUsername();
			$password =$userToAdd->getPassword();
			assert(isset($username) && isset($password));
			if(strlen($username) >= 3 && strlen($password) >= 6) {

				$userExists = false;
				foreach ($this->users as $key => $user) {
					if($username == $user->getUsername()) {
						$userExists = true;

					}
				}

				$newUser = new User($username, $password);
				$username =$newUser->getUsername();
				$password =$newUser->getPassword();

				if(!$userExists) {
				
					$stmt = $this->db->db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
					$stmt->bindParam(':username', $username);
					$stmt->bindParam(':password', $password);
					try {
						$stmt -> execute();
					}
					catch(\Exception $e) {
						throw new \exceptions\FailedRegistrationException('User already exists');
						
					}

					$this->users[] = $userToAdd;
					return true;

				}

				else if($userExists) {
					throw new \exceptions\FailedRegistrationException('User already exists');
				}
			}

			else throw new \exceptions\FailedRegistrationException('Too short password or username');

		}

		
	}