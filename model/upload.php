<?php
if(isset($_POST['btn-upload']))
{
    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $fileData = $_FILES['file']['tmp_name'];

    uploadFile($fileName, $fileType, $fileSize, $fileData);
}
