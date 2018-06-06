<?php
/**
 * Created by PhpStorm.
 * User: micha
 * Date: 5/23/2018
 * Time: 11:35 AM
 */
require('dbfunctions.php');
$dbh = connect();
if (isset($_POST['cost'])) {
    $cost = $_POST['cost'];
    $title = $_POST['title'];
    $cosignor = $_POST['cosignor'];
    $profit = $cosignor - $cost;
    updateCost($title,$cost);
    updateProfit($title,$profit);
    $percentMargin = (($cosignor - $cost) / $cosignor) * 100;
    addPercentMargin($title, $percentMargin);

}