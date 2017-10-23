<?php
include("class/report.php");
$sawId=$_GET['sawId'];
$weight_no=$_GET['weight_no'];
$ob_report= new report;
$response[]=$ob_report->getdetailReportRealTimeImage($sawId,$weight_no);
$data= json_encode($response);
echo $data;
//var_dump($response);
