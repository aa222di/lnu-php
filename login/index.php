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

$StandardUser = new model\Anonymous('Admin', 'Password');
//$users->createNewUser($StandardUser);


// Create the login object
$loginModel = new model\Login($users);



//CREATE OBJECTS OF THE VIEWS
$lv = new view\LayoutView($loginModel);


$navController = new \controller\NavigationController( $lv ,$loginModel, $users);

$navController->indexAction();