<?php
require_once("class/database.php");
date_default_timezone_set("Asia/Bangkok");
class report
{

function reportRealTime($saw_id,$shortname)
{
$datenow=date("Y-m-d");
$datenow="2017-04-01";
$clsMyDB = new MyDatabase();
$strCondition2 = "SELECT  SUM(`weight_total`)/1000 as weight_total,SUM(`price_total`) as price_total,COUNT(*) as transaction_count  FROM  raw  WHERE  `saw_id` ='$saw_id' and date(`datetime_out`)='$datenow' ";
$objSelect2 = $clsMyDB->fncSelectRecord($strCondition2);
if(!$objSelect2)
{
$response="no";
}
else{
//$objdata=$objSelect2;
if($objSelect2[0]['price_total'] != 0){
$price_total_per_kg=$objSelect2[0]['weight_total']/$objSelect2[0]['price_total'];
}else{
$price_total_per_kg=0;
}
$response[] =
[
  'sawId' => $saw_id,
  'shortname' => $shortname,
  'weight_total' => $objSelect2[0]['weight_total'],
  'price_total' => $objSelect2[0]['price_total'],
  'transaction_count' => $objSelect2[0]['transaction_count'],
  'price_total_per_kg' => $price_total_per_kg,
];
}
return $response;
}//function adduser

}//class

?>
