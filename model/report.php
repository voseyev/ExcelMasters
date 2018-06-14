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
if(isset($_POST['startDate']) && isset($_POST['endDate']) && isset($_POST['title'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $title = $_POST['title'];
    $title = '%'.$title.'%';  //% used for sql wildcard
} else if(isset($_POST['startDate']) && isset($_POST['endDate'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $title = '%%';
} else {  //if search not sent use default date
    $endDate = strftime('%F');
    //$endDate = '2018-03-30';
    $startDate = explode('-', $endDate);
    $year = $startDate[0];
    //$month = $startDate[1];
    $month = '01';
    $day = '01';
    $startDate = $year . "-" . $month . "-" . $day;
    $title = '%%';
}
//get item data
$result = selectData($title, $startDate, $endDate);
$allResult = selectAllData();
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
$totalPercent = 0;
foreach ($result as $row => $item) {
    //returned columns:  item_num, title, end_date, win, pristine, cosignor, cost
    $numRows++;
    $totalWin += $item['win'];
    $totalCons += $item['cosignor'];
    if ($item['cost'] != null) {
        $totalCost += $item['cost'];
        $numCost++;
    }
    if ($item['profit'] != null) $totalMargin += $item['profit'];
    if ($item['percent_margin'] != null) $totalPercent += $item['percent_margin'];
}

//checking which values are set to prevent dividing by 0
if ($numCost != 0) {
    $avgWin = $totalWin / $numRows;
    $avgCons = $totalCons / $numRows;
    $avgCost = $totalCost / $numCost;
    $avgMargin = $totalMargin / $numCost;
    $avgPercent = $totalPercent / $numCost;
} else if ($numRows != 0) {
    $avgWin = $totalWin / $numRows;
    $avgCons = $totalCons / $numRows;
    $avgCost = 0;
    $avgMargin = 0;
    $avgPercent = 0;
} else {
    $avgWin = 0;
    $avgCons = 0;
    $avgCost = 0;
    $avgMargin = 0;
    $avgPercent = 0;
}

//  Timeline Builder
$timeline = array();
foreach ($result as $row => $item) {
    //returned columns:  item_num, title, end_date, win, pristine, cosignor, cost
    //date format (2008, 3, 4)
    $date = str_replace("-", ", ", $item['end_date']);
    $parts = explode('-', $item['end_date']);
    $parts[1] = (double)$parts[1] - 1;  //offsets month to accommodate for date object
    $date = $parts[0].', '.$parts[1].', '.$parts[2];
    $profit = $item['profit'];

    if ($timeline[$date] == null) {  //sets default for new date
        $timeline[$date][] = array($profit, 1);
    } else {  //recalculates average for existing dates
        $avgOld = $timeline[$date][0][0];
        $count = $timeline[$date][0][1];
        $count++;
        $avgNew = ($avgOld + (($profit - $avgOld) / $count));
        $timeline[$date][0][0] = $avgNew;
        $timeline[$date][0][1] = $count;
    }
}//end timeline builder

//  AllItem Builder
$alltitle = array();
foreach ($allResult as $row => $item) {
    //returned columns:  item_num, title, end_date, win, pristine, cosignor, cost
    //date format (2008, 3, 4)
    $profit = $item['profit'];
    $tempTitle = $item['title'];

    if ($alltitle[$tempTitle] == null) {  //sets default for new date
        $alltitle[$tempTitle][] = array($profit, 1);
    } else {  //recalculates average for existing dates
        $avgOld2 = $alltitle[$tempTitle][0][0];
        $count2 = $alltitle[$tempTitle][0][1];
        $count2++;
        $avgNew2 = ($avgOld2 + (($profit - $avgOld2) / $count2));
        $alltitle[$tempTitle][0][0] = $avgNew2;
        $alltitle[$tempTitle][0][1] = $count2;
    }
}//end timeline builder



//echo '<pre>';  print_r($timeline);  echo '</pre>';

$title = str_replace("%","",$title);  //removed ampersands for displaying
$f3->set('timeline', $timeline);
$f3->set('allTitle', $alltitle);
$f3->set('title', $title);
$f3->set('startDate', $startDate);
$f3->set('endDate', $endDate);
$f3->set('totalItems', $numRows);
$f3->set('totalNoCost', $numRows - $numCost);
$f3->set('totalWin', number_format($totalWin, 2));
$f3->set('totalCons', number_format($totalCons, 2));
$f3->set('totalCost', number_format($totalCost, 2));
$f3->set('totalMargin', number_format($totalMargin, 2));
$f3->set('totalPercent', number_format($totalPercent, 2));
$f3->set('avgWin', number_format($avgWin, 2));
$f3->set('avgCons', number_format($avgCons, 2));
$f3->set('avgCost', number_format($avgCost, 2));
$f3->set('avgMargin', number_format($avgMargin, 2));
$f3->set('avgPercent', number_format($avgPercent, 2));

$template = new Template();
echo $template->render('views/reports.html');