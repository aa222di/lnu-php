<?php
//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//INCLUDE THE FILES NEEDED...
require_once('autoloader.php');
session_start();


// CONNECT TO DATABASE
	$user = 'toeswade';
	$pwd = 'password123';

// SMALL CHECK TO HAVE THE SAME CODE ON SERVER AND LOCALHOST
if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$user = 'root';
	$pwd = 'root';
}
$db = new model\Database( 'localhost', 'toeswade', $user, $pwd);




//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');


// Create user 
$users = new model\UserCollection($db);
$users->createNewUser('Admin', 'Password');

// Create the login object
$loginModel = new model\Login($users);



//CREATE OBJECTS OF THE VIEWS
$v = new view\LoginView($loginModel);
$dtv = new view\DateTimeView();
$lv = new view\LayoutView($loginModel);

$loginController = new \controller\LoginController( $loginModel,$v);

$message = $loginController->indexAction();


$lv->render( $v, $dtv, $message );

