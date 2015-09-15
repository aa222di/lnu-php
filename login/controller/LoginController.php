<?php

namespace controller; 
	
	class LoginController {

		private $loginModel;

		public function __construct( \model\Login $loginModel ) {
			$this->loginModel = $loginModel;
		}

		public function login($username, $password) {
			
			if(empty($username)) {
				throw new \Exception("Username is missing");
			}
			elseif(empty($password)) {
				throw new \Exception("Password is missing");
			}

			else {
				return $this->loginModel->login($username, $password);
			}
			
		}
	}