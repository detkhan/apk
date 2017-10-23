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

public function getDataPro_name($saw_id,$MONTH,$YEAR,$pro_name,$type)
{
$clsMyDB = new MyDatabase();
$strCondition2 = "
SELECT SUM(weight_total)/1000 as weight_total,date(datetime_out) as date_data   FROM `raw` WHERE
 `pro_name` LIKE '%$pro_name%'  AND saw_id = '$saw_id' and MONTH(datetime_out)='$MONTH'
 and YEAR(datetime_out)='$YEAR' and type_name = '$type'  GROUP BY date(datetime_out) ORDER BY date(datetime_out) ASC ";
   $objSelect2 = $clsMyDB->fncSelectRecord($strCondition2);
   if(!$objSelect2)
   {
   $response="no";
   }
   else{
     foreach ($objSelect2 as $value) {
       $response[] =
       [
         'date_data' => date("d",strtotime($value['date_data'])),
         'weight_total' => $value['weight_total'],
       ];
     }
   }
     return $response;
}

public function getlistDay($MONTH,$YEAR)
{
for($d=1; $d<=31; $d++)
{
    $time=mktime(12, 0, 0, $MONTH, $d, $YEAR);

    if (date('m', $time)==$MONTH){
            $response[]=date('d', $time);
    }
}
return $response;
}

public function getWood_pieces($saw_id,$MONTH,$YEAR)
{
  $clsMyDB = new MyDatabase();
  $strCondition2 = "
  SELECT timber_saw,total,losts,datetime as date_wood   FROM `wood_pieces` WHERE
   sawId = '$saw_id' and MONTH(datetime)='$MONTH'
   and YEAR(datetime)='$YEAR'  ORDER BY date(datetime) ASC ";
     $objSelect2 = $clsMyDB->fncSelectRecord($strCondition2);
     if(!$objSelect2)
     {
     $response="no";
     }
     else{
       foreach ($objSelect2 as $value) {
         $response[] =
         [
           'date_wood' => date("d",strtotime($value['date_wood'])),
           'timber_saw' => $value['timber_saw'],
           'total' => $value['total'],
           'losts' => $value['losts'],
         ];
       }
     }
       return $response;
}

public function getList($response1,$response2,$response3,$list_day)
{
foreach ($list_day as $key =>  $value) {
$response[$value]['wood_income']=$this->getresponse1($value,$response1);
$response[$value]['wood_sale']=$this->getresponse2($value,$response2);
$responseqq=$this->getresponse3($value,$response3);
$response[$value]['timber_saw']=$responseqq['timber_saw'];
$response[$value]['total']=$responseqq['total'];
$response[$value]['losts']=$responseqq['losts'];
  }

return $response;
}

public function getresponse1($value,$response1)
{
  foreach ($response1 as  $value2) {
  if ($value==$value2['date_data']) {
        $response=$value2['weight_total'];
    }

}
return $response;
}

public function getresponse2($value,$response2)
{
  foreach ($response2 as  $value3) {
  if ($value==$value3['date_data']) {
      $response=$value3['weight_total'];
  }
    }
    return $response;
}

public function getresponse3($value,$response3)
{
  foreach ($response3 as  $key=>$value4) {

  if ($value==$value4['date_wood']) {
      $response['timber_saw']=$value4['timber_saw'];
      $response['total']=$value4['total'];
      $response['losts']=$value4['losts'];
  }
    }
    return $response;
}


}//class

?>
