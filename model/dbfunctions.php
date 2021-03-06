<?php
/**
 * Created by PhpStorm.
 * User: Tony T
 * Date: 4/25/2018
 * Time: 11:01 PM
 * @author Anthony Thompson & Michael Horn & Vlad Oseyev
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
    //Return data or null
    return $data;
}

/**
 * @param $fileName name of the file
 * @param $fileType type of the file
 * @param $fileSize size of data of the file
 * @param $fileData data inside of the file
 * @return string of the id of the created file row
 */
function uploadFile($fileName, $fileType, $fileSize, $fileData)
{
    global $dbh;

    $sql="INSERT INTO tbl_files (name,type,size,file)
      VALUES(:name, :type, :size, :file)";

    $statement = $dbh->prepare($sql);
    $statement->bindParam(':name',$fileName,PDO::PARAM_STR);
    $statement->bindParam(':type',$fileType,PDO::PARAM_STR);
    $statement->bindParam(':size',$fileSize,PDO::PARAM_INT);
    $statement->bindParam(':file',$fileData,PDO::PARAM_LOB);

    $success = $statement->execute();

    $lastId = $dbh->lastInsertId();

    //Return data or null
    return $lastId;

}

/**
 * @return array if reports from the DB
 */
function getReports()
{
    global $dbh;

    $sql = "SELECT * FROM tbl_files";

    $statement = $dbh->prepare($sql);

    $statement->execute();
    $data = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $data;
}

/**
 * @param $id column from csv
 * @param $title column from csv
 * @param $date column from csv
 * @param $win column from csv
 * @param $pristine column from csv
 * @param $cosignor column from csv
 * @param $reportId column from csv
 * @return bool if it was sent correctly
 */
function sendData($id,$title,$date,$win,$pristine,$cosignor, $reportId)
{
    global $dbh;

    //Handling the date.. Later move to a different php file
    $dateArr = explode("/",$date);
    if(strlen($dateArr[0]) === 1)
    {
        $dateArr[0] = "0" . $dateArr[0];
    }
    if(strlen($dateArr[1]) === 1)
    {
        $dateArr[1] = "0" . $dateArr[1];
    }
    $date = $dateArr[2] . "-" . $dateArr[0] . "-" . $dateArr[1];


    //Removing dollar sign from costs, functionality moved to a different file later
    $win = str_replace("$","",$win);
    $pristine = str_replace("$","",$pristine);
    $cosignor = str_replace("$","",$cosignor);

    $sql="INSERT INTO report_data (item_num,title,end_date, win,pristine,cosignor,reportId)
      VALUES(:id, :title, :date, :win,:pristine,:cosignor, :reportId)";

    $statement = $dbh->prepare($sql);

    $statement->bindParam(':id',$id,PDO::PARAM_INT);
    $statement->bindParam(':title',$title,PDO::PARAM_STR);
    $statement->bindParam(':date',$date,PDO::PARAM_STR);
    $statement->bindParam(':win',$win,PDO::PARAM_INT);
    $statement->bindParam(':pristine',$pristine,PDO::PARAM_INT);
    $statement->bindParam(':cosignor',$cosignor,PDO::PARAM_INT);
    $statement->bindParam(':reportId',$reportId,PDO::PARAM_INT);
    $success = $statement->execute();

    return $success;
}

/**
 * @param $start date of data
 * @param $end date of data
 * @return array of data within the start and end date
 */
function selectData($title, $start, $end)
{
    global $dbh;

    $sql = "SELECT item_num, title, end_date, win, pristine, cosignor, cost, profit, percent_margin 
            FROM report_data 
            WHERE end_date >= :startdate 
            AND end_date <= :enddate 
            AND title LIKE :title 
            ORDER BY end_date";
    $statement = $dbh->prepare($sql);
    $statement->bindParam(':startdate',$start,PDO::PARAM_INT);
    $statement->bindParam(':enddate',$end,PDO::PARAM_INT);
    $statement->bindParam(':title',$title,PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * @param $start date of data
 * @param $end date of data
 * @return array of data within the start and end date
 */
function selectAllData()
{
    global $dbh;

    $sql = "SELECT item_num, title, end_date, win, pristine, cosignor, cost, profit, percent_margin 
            FROM report_data 
            ORDER BY end_date";
    $statement = $dbh->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function selectCostNull()
{
    global $dbh;
    $sql = "SELECT title, id, cosignor
            FROM report_data WHERE cost = 0 GROUP BY title ORDER BY title";
    $statement = $dbh->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;


}

function deleteReport($id)
{
    global $dbh;
    $sql = "DELETE FROM tbl_files WHERE id = :id";
    $statement = $dbh->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
}

function deleteReportData($id)
{
    global $dbh;
    $sql = "DELETE FROM report_data WHERE reportId = :id";
    $statement = $dbh->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
}

function updateCost($cost, $profit, $percentMargin, $id)
{
    global $dbh;
    $sql = "UPDATE report_data
            SET cost = :cost, profit = :profit, percent_margin = :percent_margin
            WHERE id = :id";
    $statement = $dbh->prepare($sql);

    $statement->bindValue(':cost',$cost,PDO::PARAM_INT);
    $statement->bindValue(':percent_margin',$percentMargin,PDO::PARAM_INT);
    $statement->bindValue(':profit',$profit,PDO::PARAM_INT);
    $statement->bindValue(':id',$id,PDO::PARAM_INT);

    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

// Pull Data for selected title
function selectTitleInfo($title)
{
    global $dbh;
    $sql = "SELECT * FROM report_data WHERE title = :title";
    $statement = $dbh->prepare($sql);

    $statement->bindvalue(':title',$title,PDO::PARAM_STR);

    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}


function updateQuantity($title,$quantity)
{

    global $dbh;
    $sql = "UPDATE report_data
            SET quantity = :quantity WHERE title = :title";
    $statement = $dbh->prepare($sql);

    $statement->bindValue(':quantity',$quantity,PDO::PARAM_INT);
    $statement->bindvalue(':title',$title,PDO::PARAM_STR);

    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function selectQuantity()
{
    global $dbh;
    $sql = "SELECT title, id, quantity
            FROM report_data WHERE quantity = 0 GROUP BY title ORDER BY title";
    $statement = $dbh->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function selectQuantity2()
{
    global $dbh;
    $sql = "SELECT title, id, quantity
            FROM report_data WHERE quantity != 0 GROUP BY title ORDER BY title";
    $statement = $dbh->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function countNum($title)
{
    global $dbh;
    $sql = "SELECT COUNT(title) FROM report_data WHERE title = :title";
    $statement = $dbh->prepare($sql);
    $statement->bindvalue(':title',$title,PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $result = $result[0];
    //$result = $result[0];
    foreach($result as $num)
    {
        $newResult = $num;
    }
    return $newResult;
}

function selectCost()
{
    global $dbh;
    $sql = "SELECT title, id, cosignor, cost
            FROM report_data GROUP BY title ORDER BY title";
    $statement = $dbh->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}