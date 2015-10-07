<?php

namespace controller; 
	
	class LoginController {

		private $loginModel;
		private $loginView;

		public function __construct( \model\Login $loginModel, \view\LoginView $loginView ) {
			$this->loginModel = $loginModel;
			$this->loginView = $loginView;
		}

		/**
		 * Checks with the view what the user wants to do and dispatches actions to model, view and self
		 *
		 * Should be called from index to start app
		 *
		 * @return  string or void
		 */
		public function indexAction() {

			if( $this->loginView->doesUserWantToLogin() && !$this->loginModel->checkLoginStatus() ) {
			
				try {
					$this->loginAction();
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

		/**
		 * Tells model to login user
		 *
		 * @return boolean
		 */
		private function loginAction() {

				$userToBeLoggedIn = $this->loginView->getUserCredentials();
				if($userToBeLoggedIn) {

					if($this->loginModel->login( $userToBeLoggedIn )) {
						return true;
					}
					else {
						throw new \exceptions\FailedLoginException('Wrong username or password');
						
					}
				}
				else {
					throw new \exceptions\FailedLoginException('User credentials are not complete');
						
				}

		}



		/**
		 * Tells model to logout user
		 *
		 * @return boolean
		 */
		private function logoutAction() {
			
			$message = $this->loginModel->logout();

			return $message;
			
		}
	}