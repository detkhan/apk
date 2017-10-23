<?php
include("class/report.php");
$saw_id=$_GET['saw_id'];
$datereport=$_GET['datereport'];
$pro_name=$_GET['pro_name'];
$MONTH=date("m",strtotime($datereport));
$YEAR=date("Y",strtotime($datereport));
$type1="รับเชื้อเพลิง";
$type2="เบิกเชื้อเพลิง";
$ob_report= new report;
//var_dump($MONTH);
$response1=$ob_report->getDataPro_name($saw_id,$MONTH,$YEAR,$pro_name,$type1);
$response2=$ob_report->getDataPro_name($saw_id,$MONTH,$YEAR,$pro_name,$type2);
$list_day=$ob_report->getlistDay($MONTH,$YEAR);
$response3=$ob_report->getWood_pieces($saw_id,$MONTH,$YEAR);
$response=$ob_report->getList($response1,$response2,$response3,$list_day);
/*
$response[0] =
[
  'timber_saw' => "100",
  'wood_sale' => "50",
  'total' => "1000",
  'lost' => "20",
];
*/
$data= json_encode($response);
echo $data;
//var_dump($response);
//var_dump($response1);
//var_dump($response2);
//var_dump($response3);
//var_dump($list_day);
