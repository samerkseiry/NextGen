<?php

// include 'database/connection.php' ;
include 'classes/connection.php' ;
include 'classes/User.php' ;
include 'classes/Follow.php' ;
include 'classes/Tweet.php' ;
include 'classes/Community.php' ;
session_start();
 
global $pdo;
$pdo = Connect::connect();

// instead of using objects and decide to user static function
// $User = new User();
// $getFormFollow = new Follow($conn);
// $getFormTweet = new Tweet($conn);


define("BASE_URL" , "http://localhost/nextgen/");



