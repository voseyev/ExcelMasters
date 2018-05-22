<?php
/**
 * Created by PhpStorm.
 * User: micha
 * Date: 5/22/2018
 * Time: 9:43 AM
 */
global $dbh;
echo "buttonclicked.";
$id = $_POST['id'];
$sql = "DELETE FROM tbl_files
                    WHERE id=:id";
$statement = $dbh->prepare($sql);
$statement->bindParam('id',$id,PDO::PARAM_INT);
// Execute the statement
$statement->execute();