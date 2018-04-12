<?php

// Turn on error reporting
ini_set("display_errors", 1);
error_reporting(E_ALL);


require_once ('vendor/autoload.php');
session_start();
require("model/db-functions.php");

$f3 = Base::instance();

//Set debug level
$f3->set('DEBUG', 3);

//Connect to the database
$dbh = connect();

//Login page
$f3 -> route('GET|POST /', function($f3) {

}