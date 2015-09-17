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
			$message = null;
			// Check with the view for post

			// Dispatch to the right action
			if( $this->loginView->doesUserWantToLogin() && !$this->loginModel->checkLoginStatus() ) {
			
				$message = $this->loginAction($this->loginView->getUsername(), $this->loginView->getPassword());
				
			}
			elseif($this->loginView->doesUserWantToLogout()) {
				$message = $this->logoutAction();
			}
			else {
				return $message;
			}
			return $message;
			
		}


		private function loginAction( $username, $password ) {

		
				try {
					$message = $this->loginModel->login( $username, $password );
					
					if( $this->loginView->doesUserWantToStayLoggedIn() ) {
						$message = $this->stayLoggedInAction();
					}
				}
				catch ( \Exception $e ) {
					 $message = $e->getMessage();
				}

				return $message;
	
		}


		private function stayLoggedInAction() {

		
				/*try {
					$message = $this->loginModel->keepUserLoggedIn();
				}
				catch ( \Exception $e ) {
					 $message = $e->getMessage();
				}*/
				$message = "Welcome and you will be remembered";

				return $message;
	
		}

		private function logoutAction() {
			
			$message = $this->loginModel->logout();

			return $message;
			
		}
	}