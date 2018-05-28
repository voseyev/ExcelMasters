<?php
//login check
if(empty($_SESSION['username']))
{
    header("Location:inventory_management/ExcelMasters/");
}

//submit for file upload
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



/* Setting date range for select data*/
if(isset($_POST['startDate']) && isset($_POST['endDate'])) {
    echo "post set";
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
} else {  //if search not sent use default date
    //$currentDate = strftime('%F');
    //TODO change back to dynamic current date
    $endDate = '2018-03-10' . "//////";
    $startDate = explode('-', $endDate);
    $year = $startDate[0];
    $month = $startDate[1];
    $day = '01';
    $startDate = $year . "-" . $month . "-" . $day;
    echo "Tony: " . $startDate . "xxx" . $endDate;
}
//get item data
$result = selectData($startDate, $endDate);
//get file data to display from database
$reports = getReports();
$f3->set('reports',$reports);
$f3->set('data', $result);

//calculate summary info
$numRows = 0;
$numCost = 0;
$totalWin = 0;
$totalCons = 0;
$totalCost = 0;
$totalMargin = 0;
foreach ($result as $row => $item) {
    //returned columns:
    //item_num, title, end_date, win, pristine, cosignor, cost
    //print_r($item);
    $numRows++;
    $totalWin += $item['win'];
    $totalCons += $item['cosignor'];
    if ($item['cost'] != null) {
        $totalCost += $item['cost'];
        $numCost++;
    }
    if ($item['profit'] != null) $totalMargin += $item['profit'];
}

//checking which values are set to prevent dividing by 0
if ($numCost != 0) {
    $avgWin = $totalWin / $numRows;
    $avgCons = $totalCons / $numRows;
    $avgCost = $totalCost / $numCost;
    $avgMargin = $totalMargin / $numCost;
} else if ($numRows != 0) {
    $avgWin = $totalWin / $numRows;
    $avgCons = $totalCons / $numRows;
    $avgCost = 0;
    $avgMargin = 0;
} else {
    $avgWin = 0;
    $avgCons = 0;
    $avgCost = 0;
    $avgMargin = 0;
}

$f3->set('startDate', $startDate);
$f3->set('endDate', $endDate);
$f3->set('totalItems', $numRows);
$f3->set('totalNoCost', $numRows - $numCost);
$f3->set('totalWin', $totalWin);
$f3->set('totalCons', $totalCons);
$f3->set('totalCost', $totalCost);
$f3->set('totalMargin', $totalMargin);
$f3->set('avgWin', $avgWin);
$f3->set('avgCons', $avgCons);
$f3->set('avgCost', $avgCost);
$f3->set('avgMargin', $avgMargin);

$template = new Template();
echo $template->render('views/reports.html');