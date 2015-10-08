<?php

namespace controller; 
	
	class NavigationController {


		private $navigationView;
		private $loginModel;
		private $userCollection;
		private $layoutView;



		public function __construct( \view\LayoutView $layoutView ,\model\Login $loginModel, \model\UserCollection $userCollection  ) {

			$this->navigationView = new \view\NavigationView();
			$this->loginModel = $loginModel;
			$this->userCollection = $userCollection;
			$this->layoutView = $layoutView;
		}

		/**
		 * Checks with the view what the user wants to do and dispatches actions to model, view and controllers
		 *
		 * Should be called from index to start app
		 *
		 * @return  string or redirect to self// Is this relly good structure?
		 */
		public function indexAction() {

			$message = null;
			$view = null;
			$dtv = new \view\DateTimeView();

			if($this->navigationView->doesUserWantToSeeRegPage()) {
				$view = new \view\RegistrationView();
				$regController = new RegistrationController($this->userCollection, $view);

				$message = $regController->indexAction();
				
				if ($this->userCollection->getRegistrationSucceeded()) {
					$this->navigationView->redirectTo($message, '');
				}

			}

			else if($this->navigationView->doesUserWantToSeeLoginPage()) {
				$view = new \view\LoginView($this->loginModel);
				$loginController = new LoginController($this->loginModel, $view);

				$message = $loginController->indexAction();

				if ($this->loginModel->checkLoginStatus()) {
					$this->navigationView->redirectTo($message);
				}	
			}
				
			if(!isset($message)) {
				$message = $this->navigationView->getSessionMessage();
			}

			return $this->layoutView->render($view, $dtv, $this->navigationView, $message);
			
		}





	}