<?php
include("class/report.php");
$saw_id=$_GET['saw_id'];
$datereport=$_GET['datereport'];
$MONTH=date("m",strtotime($datereport));
$YEAR=date("Y",strtotime($datereport));
$ob_report= new report;
$response=$ob_report->getProfitLoss($saw_id,$MONTH,$YEAR);
$data= json_encode($response);
echo $data;
