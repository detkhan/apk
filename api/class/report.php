<?php
require_once("class/database.php");
date_default_timezone_set("Asia/Bangkok");
class report
{

function reportRealTime($saw_id,$shortname,$datereport)
{
//$datenow=date("Y-m-d");
//$datenow="2017-04-01";
$clsMyDB = new MyDatabase();
$strCondition2 = "SELECT  SUM(`weight_total`)/1000 as weight_total,SUM(`price_total`) as price_total,COUNT(*) as transaction_count  FROM  raw  WHERE  `saw_id` ='$saw_id' and date(`datetime_out`)='$datereport' ";
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
}//function

function getdetailReportRealTime($saw_id,$datereport)
{
  $clsMyDB = new MyDatabase();
  $strCondition2 = "SELECT  *  FROM  raw  WHERE  `saw_id` ='$saw_id' and date(`datetime_out`)='$datereport' order by `datetime_in`";
  $objSelect2 = $clsMyDB->fncSelectRecord($strCondition2);
  if(!$objSelect2)
  {
  $response="no";
  }
  else{
    foreach ($objSelect2 as $value) {
      $response[] =
      [
        'sawId' => $saw_id,
        'weight_no' => $value['weight_no'],
        'car_register' => $value['car_register'],
        'cus_name' => $value['cus_name'],
        'pro_name' => $value['pro_name'],
        'place_name' => $value['place_name'],
        'datetime_in' => $value['datetime_in'],
        'datetime_out' => $value['datetime_out'],
        'weight_net' => ($value['weight_net']/1000),
        'price' => $value['price'],
        'bill_no' => $value['bill_no'],
        'detailtime' => date("h:i",strtotime($value['datetime_in'])),
      ];
    }


  }
  return $response;
}//function

public function getdetailReportRealTimeImage($sawId,$weight_no)
{
  $clsMyDB = new MyDatabase();
  $strCondition2 = "SELECT  *  FROM  images  WHERE  `saw_id` ='$sawId' and `weight_no`='$weight_no' order by `images_id` ASC";
  $objSelect2 = $clsMyDB->fncSelectRecord($strCondition2);

  if(!$objSelect2)
  {
  $response="no";
  }
  else{
    foreach ($objSelect2 as $value) {
      $response[] =
      [
        'file_image' => $value['file_image'],
      ];
    }
  }
    return $response;
}//function

}//class

?>
