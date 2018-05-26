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

//$currentDate = strftime('%F');
echo "Todays date (revised to march)"  . " ";
$currentDate = '2018-03-10' . "//////";
echo $currentDate . "     ";


$currentDate = explode('-', $currentDate);
$year = $currentDate[0];
$month   = $currentDate[1];
$day  = $currentDate[2];

$day = '01';

$firstDayOfMonth = $year . "-" . $month . "-" . $day;
echo " " . "First day of current days month";
echo " " . $firstDayOfMonth;


selectData($currentDate, $firstDayOfMonth);

/*Date Picker Start and end Date*/
if(isset($_POST['startDate']) && isset($_POST['endDate'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $result = selectData($startDate, $endDate);

    //get data to display from database
    $reports = getReports();
    $f3->set('reports',$reports);
    $f3->set('data', $result);

}

$template = new Template();
echo $template->render('views/reports.html');