<?php

//INCLUDE THE FILES NEEDED...
require_once('autoloader.php');
session_start();


//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');


// Create user 
$users = new model\UserCollection();
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

