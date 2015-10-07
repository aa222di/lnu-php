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

    		$stmt = $this->db->db->prepare("SELECT username, password FROM users");
    		$stmt -> execute();
    		$this->users = $stmt->fetchAll(\PDO::FETCH_CLASS, '\model\User');
		}

		/**
		* Creates new User and adds it to collection
		* @param $username string
		* @param $password string
		* @return void 
		*/
		public function createNewUser(Anonymous $userToAdd) {
			$username =$userToAdd->getUsername();
			$password =$userToAdd->getPassword();
			assert(isset($username) && isset($password));

			$User = new User($username, $password);
			$this->add($User);

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
		* @return array 
		*/
		public function getUserCollection() {
			return $this->users;
		}


		/**
		* Adds new user to collection
		* @return boolean
		*/
		private function add( User $userToAdd) {
			
			$username =$userToAdd->getUsername();
			$password =$userToAdd->getPassword();
			assert(isset($username) && isset($password));

			$userExists = false;
			foreach ($this->users as $key => $user) {
				if($username == $user->getUsername()) {
					$userExists = true;

				}
			}

			if(!$userExists) {
			
				$stmt = $this->db->db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
				$stmt->bindParam(':username', $username);
				$stmt->bindParam(':password', $password);
				$stmt -> execute();

				$this->users[] = $userToAdd;
				return true;

			}

			else if($userExists) {
				throw new \exceptions\FailedRegistrationException('User already exists');
			}
		}

		
	}