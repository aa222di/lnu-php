<?php

namespace controller; 
	
	class LoginController {

		private $loginModel;
		private $loginView;

		public function __construct( \model\Login $loginModel, \view\LoginView $loginView ) {
			$this->loginModel = $loginModel;
			$this->loginView = $loginView;
		}


		public function indexAction() {

			if( $this->loginView->doesUserWantToLogin() && !$this->loginModel->checkLoginStatus() ) {
			
				try {
					$this->loginAction($this->loginView->getUsername(), $this->loginView->getPassword());
					return $this->loginView->loginMessage();
				}
				catch (\exceptions\FailedLoginException $e) {
					return $this->loginView->handleError();
				} 
				
			}

			elseif($this->loginView->doesUserWantToLogout() && $this->loginModel->checkLoginStatus()) {
				$this->logoutAction();
				return $this->loginView->logoutMessage();
			}

			else {
				return;
			}
	
			
		}


		private function loginAction( $username, $password ) {

				if($this->loginModel->login( $username, $password )) {
					return true;
				}
				else {
					throw new \exceptions\FailedLoginException('Wrong username or password');
					
				}
	
		}



		private function logoutAction() {
			
			$message = $this->loginModel->logout();

			return $message;
			
		}
	}