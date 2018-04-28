<?php
if(isset($_POST['btn-upload']))
{
    $fileName = $_FILES['file']['name'];
    $fileLoc = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];

    uploadFile($fileName, $fileType, $fileSize);
}
