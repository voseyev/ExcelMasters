<?php

$result = selectData(1,2);  //TODO replace with dates

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";


    }
} else {
    echo "0 results";
}







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

    $fp = fopen($_FILES['file']['tmp_name'],'rb');
    while(($line = fgets($fp)) !== false)
    {
        $newWord = "";
        $arr = explode(',',$line);
        if(is_numeric($arr[0]))
        {
            print_r($arr);
            echo "<br>";
            sendData($arr[0],$arr[2],$arr[3],$arr[4],$arr[5],$arr[6]);
        }
    }

    //uploadFile($fileName, $fileSize, $fileType, $fileData);
}



$reports = getReports();
$f3->set('reports',$reports);

$template = new Template();
echo $template->render('views/reports.html');

