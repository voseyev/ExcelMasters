<?php

session_start();

require_once ('vendor/autoload.php');

$f3 = Base::instance();

//Set debug level
$f3->set('DEBUG', 3);

//Login page
$f3 -> route('GET|POST /', function($f3) {
    $database = new Database();


//    if(isset($_POST['submit']))
//    {
//        header("Location: reports");
//    }

        $_SESSION['username'] = "";
        $_SESSION['password'] = "";

        //if login page was submitted by user
        if(isset($_POST['login']))
        {
            //if the username and password are not null
            if(!is_null($_POST['username'] && !is_null($_POST['password'])))
            {
                $username = $_POST['username'];
                $password = $_POST['password'];
                //checks the database if correct
                $data = $database->checkUser($username,$password);
                //returns 1 if correct, otherwise 0
                if($data == 1)
                {
                    //sets the session
                    $_SESSION['username'] = $username;
                    $_SESSION['password'] = $password;
                    $f3->reroute('/reports');
                } else {
                    echo "Wrong Login";
                }
            }
        }
        $template = new Template();
        echo $template->render('views/login.html');

});

$f3-> route('GET|POST /reports', function($f3) {

    $template = new Template();
    echo $template->render('views/reports.html');
});

//Run Fat-Free Framework
$f3->run();