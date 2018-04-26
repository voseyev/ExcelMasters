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

require("/home/aaronavi/config.php");

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


