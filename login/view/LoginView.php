<?php
namespace view;

class LoginView {
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
				setcookie(self::$cookieName, $_POST[self::$name]);
				setcookie(self::$cookiePassword, $this->loginModel->getTemporaryPassword($_POST[self::$password]));

			}
			
			$response = $this->generateLogoutButtonHTML($message);
		}

		else {

			$response = $this->generateLoginFormHTML($message);
		}
		
		
		return $response;
	}



	public function doesUserWantToLogin() {

		if(isset($_POST[self::$login]) && isset($_POST[self::$password]) && isset($_POST[self::$name])) {
			return true;
		}
		return false;
	}

	public function doesUserWantToStayLoggedIn() {

		if(isset($_POST[self::$login]) && isset($_POST[self::$keep])) {
			return true;
		}
		return false;
	}

	public function doesUserWantToLogout() {

		if(isset($_POST[self::$logout])) {
			return true;
		}
		return false;
	}

	public function getUsername() {

		if(isset($_POST[self::$login]) && isset($_POST[self::$name])) {
			return $_POST[self::$name];
		}
		return false;
	}

	public function getPassword() {

		if(isset($_POST[self::$login]) && isset($_POST[self::$password])) {
			return $_POST[self::$password];
		}
		return false;
	}



	public function loginMessage() {

		return "Welcome";

	}


	public function logoutMessage() {

		return "Bye bye!";

	}



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
		else {
			$message = "Login button isn't pressed";
		}

		return $message;

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