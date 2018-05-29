<?php

// Turn on error reporting
ini_set("display_errors", 1);
error_reporting(E_ALL);

session_start();

require_once ('vendor/autoload.php');

$f3 = Base::instance();
require("model/dbfunctions.php");

//Set debug level
$f3->set('DEBUG', 3);

$dbh = connect();

//Login page
$f3 -> route('GET|POST /', function($f3) {

    $_SESSION['username'] = "";
    $_SESSION['password'] = "";
    //if login page was submitted by user
    if(isset($_POST['submit']))
    {
        //if the username and password are not null
        if(!is_null($_POST['username'] && !is_null($_POST['password'])))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            //checks the database if correct
            $data = checkUser($username,$password);
            if(!empty($data['username']))
            {
                $_SESSION['username'] = $username;
                $_PASSWORD['password'] = $password;
                header("Location:reports");
            } else
                {
                echo "nope";
                }
        }
    }
    $template = new Template();
    echo $template->render('views/login.html');

});

$f3-> route('GET|POST /reports', function($f3) {
    require("model/report.php");

});

$f3 -> route('GET|POST /editReports', function($f3)
{
    if(empty($_SESSION['username']))
    {
        header("Location:inventory_management/ExcelMasters/");
    }
    //display all reports
    $reports = getReports();
    $f3->set('reports',$reports);

    //Be able to delete reports
    //Be able to edit costs of certain report items
    $data = selectCostNull();
    $f3->set('data',$data);

    $template = new Template();
    echo $template->render('views/editReports.html');
});

//upload
$f3-> route('GET|POST /upload', function($f3) {
    require("model/upload.php");
    $template = new Template();
    echo $template->render('views/upload.html');
});

//test
$f3-> route('GET|POST /test', function($f3) {
    $template = new Template();
    echo $template->render('views/test.html');
});

//Run Fat-Free Framework
$f3->run();