<?php
namespace view;

class LoginView implements IView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private static $loggedIn = 'LoginView::LoggedIn';


	private $loginModel;
	private $userCollection;
	private $loggedInWithCookies = false;

	public function __construct( \model\Login $loginModel) {
		
		$this->loginModel = $loginModel;
		
	}


	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response( $message = null ) {
		
		if($this->loginModel->checkLoginStatus()) {

			if ( $this->doesUserWantToStayLoggedIn() ) {
				$temp = $this->loginModel->getTemporaryPassword($_POST[self::$name] ,$_POST[self::$password]);
				
				$this->handleCookies(self::$cookieName, $_POST[self::$name], true);
				$this->handleCookies(self::$cookiePassword, $temp, true);
			}
			
			$response = $this->generateLogoutButtonHTML($message);
		}

		else {

			$response = $this->generateLoginFormHTML($message);
		}
		
		
		return $response;
	}


	// CHECK USER ACTION METHODS

	/**
	 * Checks if user wants to login or has a cookie that suggests so.
	 * To be called from controller
	 * @return  boolean
	 */
	public function doesUserWantToLogin() {

		if(isset($_POST[self::$login]) && isset($_POST[self::$password]) && isset($_POST[self::$name])) {

			return true;
		}
		elseif (isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword])) {
			return true;
		}
		return false;
	}


	/**
	 * Checks if user wants to logout
	 * To be called from controller
	 * @return  boolean and unsets cookies
	 */
	public function doesUserWantToLogout() {

		if(isset($_POST[self::$logout])) {

			if(isset($_COOKIE[self::$cookieName])) {
				$this->handleCookies(self::$cookieName);
			}

			if(isset($_COOKIE[self::$cookiePassword])) {
				$this->handleCookies(self::$cookiePassword);
			}

			return true;
		}

		return false;
	}


	/**
	 * Retrieves username and password 
	 * @return  model\User $User or boolean
	 */
	public function getUserCredentials() {

		$username = $this->getUsername();
		$password = $this->getPassword();
		if($password && $username) {
			return new \model\Anonymous($username, $password);
		}
		elseif (!$password) {
			$password = $this->getTempPassword();

			if($password && $username) {
				return new \model\Anonymous($username, $password);
			}
		}
		else {
			return false;
		}
	}


	// GET USER INPUT METHODS

	/**
	 * Retrieves username from POST or COOKIE
	 * @return  string or boolean
	 */
	private function getUsername() {

		if(isset($_POST[self::$login]) && isset($_POST[self::$name])) {
			$this->loginWithCookies = false;
			return $_POST[self::$name];

		}
		elseif (isset($_COOKIE[self::$cookieName])) {
			$this->loginWithCookies = true;
			return $_COOKIE[self::$cookieName];
		}
		return false;
	}


	/**
	 * Retrieves password from POST or COOKIE
	 * @return  string or boolean
	 */
	private function getPassword() {

		if(isset($_POST[self::$login]) && isset($_POST[self::$password])) {
			return $_POST[self::$password];
		}
		return false;
	}


	/**
	 * Retrieves password COOKIE
	 * @return  string or boolean
	 */
	private function getTempPassword() {

		if (isset($_COOKIE[self::$cookiePassword])) {
			return $_COOKIE[self::$cookiePassword];
		}
		return false;
	}

	
	/**
	 * Checks if user wants to login or has a cookie that suggests so.
	 * @return  boolean
	 */
	private function doesUserWantToStayLoggedIn() {

		if(isset($_POST[self::$login]) && isset($_POST[self::$keep])) {
			return true;
		}
		return false;
	}


	// GET APP OUTPUT METHODS

	/**
	 * Handles login success message
	 * @return  string
	 */
	public function loginMessage() {

		if ( $this->doesUserWantToStayLoggedIn() ) {
		
			return "Welcome and you will be remembered";		

		}
		elseif ( !isset($_POST[self::$login])) {
			return "Welcome back with cookie";
		}
		else {
					return "Welcome";
		}


	}

	/**
	 * Handles logout success
	 * @return  string
	 */
	public function logoutMessage() {

		return "Bye bye!";

	}

	/**
	 * Handles possible errors and returns userfriendly message
	 * @return  string
	 */
	public function handleError() {

		$message = null;

		if(isset($_POST[self::$login])) {
			if(empty($_POST[self::$name])) {
				$message = "Username is missing";
			}
			elseif(empty($_POST[self::$password])) {
				$message = "Password is missing";
			}
			else {
				$message = "Wrong name or password";
			}
		}
		elseif (isset($_COOKIE[self::$cookieName]) || isset($_COOKIE[self::$cookiePassword])) {
			$this->handleCookies(self::$cookieName);
			$this->handleCookies(self::$cookiePassword);
			$message = "Wrong information in cookies";
		}
		else {
			$message = "Login button isn't pressed";
		}

		return $message;

	}


	/**
	 * Sets or unsets cookies
	 * @param $cookieLocation string. Array position in cookie
	 * @param $set, boolean. True - set cookie, false unset cookie. Defaults to true
	 * @param $value, string, only used to set cookie
	 * @return  void
	 */
	public function handleCookies( $cookieLocation, $value=null, $set=false ) {

		if($set) {
			setcookie($cookieLocation, $value, time() + (30 * 24 * 60 * 60)); 
		}
		else {
			unset($_COOKIE[$cookieLocation]);
			setcookie($cookieLocation, "", time() - 3600);
		}
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		
		$username = null;

		if (isset($_POST[self::$name])) {
			$username = $_POST[self::$name];
		}
		else {
			$username = \model\UserCollection::getRegisteredUser();
		}

		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $username . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

}