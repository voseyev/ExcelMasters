<?php
/**
 * Created by PhpStorm.
 * User: micha
 * Date: 6/6/2018
 * Time: 9:11 AM
 */

require('dbfunctions.php');
$dbh = connect();
if (isset($_POST['quantity'])) {
    $quantity = $_POST['quantity'];
    $title = $_POST['title'];
    updateQuantity($title,$quantity);

}