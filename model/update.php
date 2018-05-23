<?php
/**
 * Created by PhpStorm.
 * User: micha
 * Date: 5/23/2018
 * Time: 11:35 AM
 */
require('dbfunctions.php');
$dbh = connect();
if (isset($_POST['cost']) && isset($_POST['title'])) { //if we get the name successfully
    $cost = $_POST['cost'];
    $title = $_POST['id'];
    updateCost($title,$cost);
}