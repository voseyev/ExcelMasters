<?php
/**
 * Created by PhpStorm.
 * User: Vlado
 * Date: 4/25/2018
 * Time: 2:59 PM
 */

/**
 * Checks if user is valid
 * @param $username
 * @param $password
 * @return bool
 */
function checkUser($username, $password)
{
    //Query the db
    $sql = "SELECT * FROM Admin
                    WHERE username=$username and password= $password";
    $statement = $this->dbh->prepare($sql);
    // Execute the statement
    $row = $statement->execute();
    //Return true if a match found, false otherwise
    return $row;
}