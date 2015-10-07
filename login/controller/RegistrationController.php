<?php

namespace controller; 
	
	class RegistrationController {


		private $userCollection;
		private $registrationView;

		public function __construct( \model\UserCollection $userCollection, \view\RegistrationView $registrationView ) {
			$this->userCollection = $userCollection;
			$this->registrationView = $registrationView;
		}

		/**
		 * Checks if user want to register and if so tries to register user
		 * @return  string 
		 */
		public function indexAction() {

			if( $this->registrationView->doesUserWantToRegister()) {
				$user = $this->registrationView->getUserCredentials();

				if($user) {
					try {
						$this->userCollection->createNewUser($user);

						return $this->registrationView->registrationSuccess();
					}
					catch (\exceptions\FailedRegistrationException $e) {
						return $this->registrationView->handleError();
					} 
				}
				else {
					return $this->registrationView->handleError();
				}
				
			}
			return;
		}



	}