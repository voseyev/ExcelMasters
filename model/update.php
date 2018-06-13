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
    $result = selectTitleInfo($title);
    foreach ($result as $row => $item) {
        $cosignor = $item['cosignor'];
        $profit = $cosignor - $cost;
        $percentMargin = (($profit / $cosignor) * 100);
        updateCost($title, $cost, $profit, $percentMargin);
    }
}