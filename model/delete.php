<?php
/**
 * Created by PhpStorm.
 * User: micha
 * Date: 5/22/2018
 * Time: 9:43 AM
 */
echo "Inside file 1";
require('dbfunctions.php');
$dbh = connect();
echo "Deletion is commencing...";
if (isset($_POST['id'])) { //if we get the name successfully
    $id = $_POST['id'];
    deleteReport($id);
}