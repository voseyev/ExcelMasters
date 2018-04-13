<?php

require_once ('vendor/autoload.php');

$f3 = Base::instance();

//Set debug level
$f3->set('DEBUG', 3);

//Login page
$f3 -> route('GET|POST /', function($f3) {
    if(isset($_POST['submit']))
    {
        header("Location: reports");
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