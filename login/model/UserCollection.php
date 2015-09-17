<?php

namespace model; 

	class UserCollection {

		private $users = [];
		public $db;

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

			$username =$userToAdd->getUsername();
			$password =$userToAdd->getPassword();

			$stmt = $this->db->db->prepare("SELECT * FROM users where username = ?");
		
			$stmt->execute([$username]);
			$userExists = $stmt->fetch();
			if(!$userExists) {
			
				$stmt = $this->db->db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
				$stmt->bindParam(':username', $username);
				$stmt->bindParam(':password', $password);
				$stmt -> execute();

			}
		}

		
	}