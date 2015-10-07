<?php
namespace view;

class RegistrationView implements IView {
	private static $register = 'RegistrationView::Register';
	private static $name = 'RegistrationView::UserName';
	private static $password = 'RegistrationView::Password';
	private static $passwordRepeat = 'RegistrationView::PasswordRepeat';




	public function __construct( ) {
		
	
	}


	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response( $message=null ) {
		
		return $this->generateRegistrationFormHTML( $message );		

	}


	// CHECK USER ACTION METHODS

	/**
	 * Checks if user wants to register
	 * To be called from controller
	 * @return  boolean
	 */
	public function doesUserWantToRegister() {

		if(isset($_POST[self::$register]) && isset($_POST[self::$password]) && isset($_POST[self::$name])) {
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

		if(isset($_POST[self::$register]) && isset($_POST[self::$name])) {
			return $_POST[self::$name];
		}
		return false;
	}


	/**
	 * Retrieves password from POST or COOKIE
	 * @return  string or boolean
	 */
	private function getPassword() {

		if(isset($_POST[self::$register]) && isset($_POST[self::$password]) && isset($_POST[self::$passwordRepeat])) {
			if(trim($_POST[self::$passwordRepeat]) == trim($_POST[self::$password])) {
				return $_POST[self::$password];
			}
			else {
				return false;
			}
		}
		return false;
	}




	/**
	 * 
	 * @return  string
	 */
	public function registrationSuccess() {

		return "Registered new user.";

	}



	/**
	 * Handles possible errors and returns userfriendly message
	 * @return  string
	 */
	public function handleError() {

		$message = '';

		if(isset($_POST[self::$register])) {
			if(strlen(trim($_POST[self::$name])) < 3) {
				$message .= "Username has too few characters, at least 3 characters.</br>";
			}
			if(strlen(trim($_POST[self::$password])) < 6) {
				$message .= "Password has too few characters, at least 6 characters.";
			}
			else if (trim($_POST[self::$password]) !== trim($_POST[self::$passwordRepeat])) {
				$message = "Passwords do not match";
			}

			else {
				$message = "Users exists, pick another username";
			}
		}

		else {
			$message = "Login button isn't pressed";
		}

		return $message;

	}

	
	/**
	* Generate HTML code on the output buffer for the registration form
	* @return  void, BUT writes to standard output!
	*/
	private function generateRegistrationFormHTML( $message ) {
		
		$username = null;

		if (isset($_POST[self::$name])) {
			$username = $_POST[self::$name];
		}

		return '
			<form method="post" > 
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p>' . $message . '</p>
					
					<p><label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $username . '" /></p>

					<p><label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" /></p>


					<p><label for="' . self::$passwordRepeat . '">Repeat password :</label>
					<input type="password" id="' . self::$passwordRepeat . '" name="' . self::$passwordRepeat . '" /></p>

		
					
					<input type="submit" name="' . self::$register . '" value="Register" />
				</fieldset>
			</form>
		';
	}







}