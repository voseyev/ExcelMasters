<?php
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

    //Opens file contents into object
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

//get data to display from database
$reports = getReports();
$f3->set('reports',$reports);

/*Date Picker Start and end Date*/
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$result = selectData($startDate, $endDate);
if(isset($_POST['startDate']) && isset($_POST['endDate'])) {
    echo "Hello";
}
echo "Bye";
echo $startDate;
echo $endDate;
$f3->set('data', $result);

$template = new Template();
echo $template->render('views/reports.html');