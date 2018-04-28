<?php
/**
 * Created by PhpStorm.
 * User: Tony T
 * Date: 4/25/2018
 * Time: 11:01 PM
 * @author Anthony Thompson
 * @version 1.0
 * Used for functions connecting to the database
 */

require("/home2/aaronavi/db.php");

/**
 * Connect to the database with a PDO
 * @return PDO|void PDO object to be used
 */
function connect()
{
    try {
        //Instantiate a database object
        $dbh = new PDO(DB_DSN, DB_USERNAME,
            DB_PASSWORD);
        return $dbh;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return;
    }
}//end connect

/**
 * Checks if user is valid
 * @param $username
 * @param $password
 * @return bool
 */
function checkUser($username, $password)
{
    global $dbh;
    //Query the db
    $sql = "SELECT * FROM Admin
                    WHERE username=:username and password= :password";
    $statement = $dbh->prepare($sql);
    $statement->bindParam('username',$username,PDO::PARAM_STR);
    $statement->bindParam('password',$password,PDO::PARAM_STR);
    // Execute the statement
    $statement->execute();
    $data = $statement->fetch(PDO::FETCH_ASSOC);
    //Return true if a match found, false otherwise
    return $data;
}


function uploadFile($fileName, $fileType, $fileSize)
{


    $sql="INSERT INTO tbl_uploads(file,type,size) VALUES('$fileName','$file_type','$file_size')";

}