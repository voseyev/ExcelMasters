<?php
/**
 * Created by PhpStorm.
 * User: Tony T
 * Date: 4/25/2018
 * Time: 11:01 PM
 * @author Anthony Thompson & Michael Horn & Vlad Oseyev
 * @version 1.0
 * Used to test uploading files to DB
 */
if(isset($_POST['btn-upload']))
{
    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $fileData = $_FILES['file']['tmp_name'];

    uploadFile($fileName, $fileType, $fileSize, $fileData);
}
