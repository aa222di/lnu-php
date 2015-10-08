<?php
namespace view;

class NavigationView {

	private static $page = 'p';
	private static $registrationPage = 'register';
	private static $loginPage;

	private static $sessionSaveLocation = 'NavigationController::SaveMessage';




	public function __construct(  ) {
		
	
	}


	/**
	 * Creates navigation
	 * @return  HTML string
	 */
	public function response(  ) {
		
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$link = null;
		$linkText = null;
		$page = $this->checkURL();

		if($page == self::$registrationPage) {
			$link = '?';
			$linkText = 'Back to login';
		}
		else {
			$link = '?' . self::$page . '=' . self::$registrationPage;
			$linkText = 'Register a new user';
		}

		$url .= $link;

		return "<a href='" . $link . "' title='" . $linkText .  "'>" . $linkText . "</a>";


	}


	// CHECK USER ACTION METHODS

	/**
	 * Checks to which page the user has navigated
	 * To be called from controller
	 * @return  boolean
	 */
	public function doesUserWantToSeeRegPage() {

		$page = $this->checkURL();

		if($page == self::$registrationPage) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Checks to which page the user has navigated
	 * To be called from controller
	 * @return  boolean
	 */
	public function doesUserWantToSeeLoginPage() {

		$page = $this->checkURL();

		if($page == self::$registrationPage) {
			return false;
		}
		else {
			return true;
		}
	}

	/**
	 * Redirects 
	 * To be called from controller
	 * @return  boolean or string
	 */		
	public function redirectTo($message, $url=null) {

		$_SESSION[self::$sessionSaveLocation] = $message;
		if(isset($url)) {
			$link = "http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]" . $url;
		}
		else {
			$link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		}
		
		header("Location: $link");
	}


	/**
	 * Gets the session message
	 * To be called from controller
	 * @return string
	 */
	public function getSessionMessage() {

		if (isset($_SESSION[self::$sessionSaveLocation])) {
			$message = $_SESSION[self::$sessionSaveLocation];
			unset($_SESSION[self::$sessionSaveLocation]);

			return $message;
		}
			return "";
	}

	/**
	 * Checks to which page the user has navigated
	 * @return  boolean or string
	 */
	private function checkURL() {

		if(isset($_GET[self::$page])) {
			return $_GET[self::$page]; // TODO: Get settings
		}
		return false;
	}






}