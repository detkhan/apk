<?php
include("class/report.php");
$sawId=$_GET['sawId'];
$datereport=$_GET['datereport'];
$ob_report= new report;
$response[]=$ob_report->getdetailReportRealTime($sawId,$datereport);
$data= json_encode($response);
echo $data;
//var_dump($response);
