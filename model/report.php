<?php
/**
 * Created by PhpStorm.
 * User: Tony T
 * Date: 4/25/2018
 * Time: 11:01 PM
 * @author Anthony Thompson & Michael Horn & Vlad Oseyev
 * @version 1.0
 * Used for sending report data to the DB
 */
if(empty($_SESSION['username']))
{
    header("Location:inventory_management/ExcelMasters/");
}

if(isset($_POST['submit']))
{
    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $fileData = $_FILES['file']['tmp_name'];

    $lastId = uploadFile($fileName, $fileSize, $fileType, $fileData);

    $fp = fopen($_FILES['file']['tmp_name'],'rb');
    while(($line = fgets($fp)) !== false)
    {
        $newWord = "";
        $arr = explode(',',$line);
        if(is_numeric($arr[0]))
        {
            sendData($arr[0],$arr[2],$arr[3],$arr[4],$arr[5],$arr[6], $lastId);
        }
    }
}

$reports = getReports();
$f3->set('reports',$reports);


$result = selectData(1,2);  //TODO replace with dates
$f3->set('data', $result);

$template = new Template();
echo $template->render('views/reports.html');

