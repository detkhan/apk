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
$strCondition2 = "SELECT  SUM(`weight_total`) as weight_total,SUM(`price`) as price_total,COUNT(*) as transaction_count  FROM  raw  WHERE  `saw_id` ='$saw_id' and date(`datetime_out`)='$datereport' ";
$objSelect2 = $clsMyDB->fncSelectRecord($strCondition2);
if(!$objSelect2)
{
$response="no";
}
else{
//$objdata=$objSelect2;

if($objSelect2[0]['price_total'] != 0){
$price_total_per_kg=$objSelect2[0]['price_total']/$objSelect2[0]['weight_total'];
}else{
$price_total_per_kg=0;
}
if($objSelect2[0]['weight_total'] > 0){
  $weight_total=$objSelect2[0]['weight_total']/1000;
}else {
  $weight_total=0;
}
if($objSelect2[0]['price_total'] > 0){
  $price_total=$objSelect2[0]['price_total'];
}else {
  $price_total=0;
}
if($objSelect2[0]['transaction_count'] > 0){
  $transaction_count=$objSelect2[0]['transaction_count'];
}else {
  $transaction_count=0;
}
$pro_name=['ไม้ท่อน','ไม้ฟืน','ไม้เกรด','ปีกไม้','ขี้เลื่อย'];
$woodPices=$this->checkSumProduct($saw_id,$datereport,$pro_name[0]);
$fireWood=$this->checkSumProduct($saw_id,$datereport,$pro_name[1]);
$woodGade=$this->checkSumProduct($saw_id,$datereport,$pro_name[2]);
$woodWing=$this->checkSumProduct($saw_id,$datereport,$pro_name[3]);
$sawDust=$this->checkSumProduct($saw_id,$datereport,$pro_name[4]);
$response[] =
[
  'sawId' => $saw_id,
  'shortname' => $shortname,
  'weight_total' => $weight_total,
  'price_total' => $price_total,
  'transaction_count' => $transaction_count,
  'woodPices' => $woodPices,
  'fireWood' => $fireWood,
  'woodGade' => $woodGade,
  'woodWing' => $woodWing,
  'sawDust' => $sawDust,
  'price_total_per_kg' => $price_total_per_kg,
];
}
return $response;
}//function

public function checkSumProduct($saw_id,$datereport,$pro_name)
{
  switch ($pro_name) {
    case 'ไม้ท่อน':
  $sql="LIKE '%$pro_name%'";
      break;
      case 'ไม้ฟืน':
  $sql="IN ('ไม้ฟืน','ไม้ฝืน')";
  break;
  case 'ไม้เกรด':
  $sql="LIKE '%$pro_name%'";;
  break;
  case 'ปีกไม้':
  $sql="LIKE '%$pro_name%'";;
  break;
  case 'ขี้เลื่อย':
  $sql="IN ('ขี้เลื่อย','ขี่เลื่อย')";
  break;
  }
  $clsMyDB = new MyDatabase();
  $strCondition2 = "SELECT SUM(weight_total) as weight_total  FROM  raw  WHERE  `saw_id` ='$saw_id' and date(`datetime_out`)='$datereport' and pro_name $sql ";
  $objSelect2 = $clsMyDB->fncSelectRecord($strCondition2);

  if(!$objSelect2)
  {
  $response=0;
  }
  else{
  if ($objSelect2[0]['weight_total']>0) {
    $response=$objSelect2[0]['weight_total']/1000;
  }else {
    $response=0;
  }

  }
    return $response;
}

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
switch ($pro_name) {
  case 'ไม้ท่อน':
$sql="LIKE '%$pro_name%'";
    break;
    case 'ไม้ฟืน':
$sql="IN ('ไม้ฟืน','ไม้ฝืน')";
break;
case 'ไม้เกรด':
$sql="LIKE '%$pro_name%'";;
break;
case 'ปีกไม้':
$sql="LIKE '%$pro_name%'";;
break;
case 'ขี้เลื่อย':
$sql="IN ('ขี้เลื่อย','ขี่เลื่อย')";
break;
}
  $strCondition2 = "
  SELECT SUM(weight_total)/1000 as weight_total,date(datetime_out) as date_data   FROM `raw` WHERE
   `pro_name` $sql  AND saw_id = '$saw_id' and MONTH(datetime_out)='$MONTH'
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


public function getWood_grade($saw_id,$MONTH,$YEAR)
{
  $clsMyDB = new MyDatabase();
  $strCondition2 = "
  SELECT timber_saw,total,losts,datetime as date_wood   FROM `wood_grade` WHERE
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


public function getWood_wing($saw_id,$MONTH,$YEAR)
{
  $clsMyDB = new MyDatabase();
  $strCondition2 = "
  SELECT timber_saw,total,losts,datetime as date_wood   FROM `wood_wing` WHERE
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


public function getFire_wood($saw_id,$MONTH,$YEAR)
{
  $clsMyDB = new MyDatabase();
  $strCondition2 = "
  SELECT firewood_total,firewood_losts,datetime as date_wood   FROM `fire_wood` WHERE
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
           'timber_saw' => "0",
           'total' => $value['firewood_total'],
           'losts' => $value['firewood_losts'],
         ];
       }
     }
       return $response;
}

public function getSawdust($saw_id,$MONTH,$YEAR)
{
  $clsMyDB = new MyDatabase();
  $strCondition2 = "
  SELECT sawdust_total,sawdust_losts,datetime as date_wood   FROM `sawdust` WHERE
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
           'timber_saw' => "0",
           'total' => $value['firewood_total'],
           'losts' => $value['firewood_losts'],
         ];
       }
     }
       return $response;
}

public function getList($response1,$response2,$response3,$list_day)
{
foreach ($list_day as $key =>  $value) {
$response["D".$value]['wood_income']=$this->getresponse1($value,$response1);
$response["D".$value]['wood_sale']=$this->getresponse2($value,$response2);
$responseqq=$this->getresponse3($value,$response3);
$response["D".$value]['timber_saw']=$responseqq['timber_saw'];
$response["D".$value]['total']=$responseqq['total'];
$response["D".$value]['losts']=$responseqq['losts'];
  }

return $response;
}

public function getresponse1($value,$response1)
{
  if ($response1!="no") {
  foreach ($response1 as  $value2) {
  if ($value==$value2['date_data']) {
        $response=$value2['weight_total'];
    }
}
}else {
  $response=0;
}
return $response;
}

public function getresponse2($value,$response2)
{

  if ($response2!="no") {
  foreach ($response2 as  $value3) {
  if ($value==$value3['date_data']) {
      $response=$value3['weight_total'];
  }
    }
  }else {
    $response=0;
  }
    return $response;
}

public function getresponse3($value,$response3)
{

  if ($response3!="no") {
  foreach ($response3 as  $key=>$value4) {

  if ($value==$value4['date_wood']) {
      $response['timber_saw']=$value4['timber_saw'];
      $response['total']=$value4['total'];
      $response['losts']=$value4['losts'];
  }else {
    $response['timber_saw']=0;
    $response['total']=0;
    $response['losts']=0;
  }
    }
  }
  else {
    $response['timber_saw']=0;
    $response['total']=0;
    $response['losts']=0;
  }
    return $response;
}//function

public function getProfitLoss($saw_id,$month,$year)
{
  $clsMyDB = new MyDatabase();
  $strCondition2 = "
  SELECT *  FROM `profit_loss` WHERE
   sawId = '$saw_id' and MONTH(datetime)='$month'
   and YEAR(datetime)='$year'  ORDER BY date(datetime) ASC ";
     $objSelect2 = $clsMyDB->fncSelectRecord($strCondition2);
     if(!$objSelect2)
     {
     $response="no";
     }
     else{
       foreach ($objSelect2 as $value) {
         $response[] =
         [
           'date' => date("d",strtotime($value['datetime'])),
           'incoming_total' => $value['incoming_total'],
           'outcoming_total' => $value['outcoming_total'],
           'gross_profit_total' => $value['gross_profit_total'],
           'costs_total' => $value['costs_total'],
           'profit_loss_total' => $value['profit_loss_total'],
         ];
       }
     }
       return $response;
}//function

public function getPerformance($saw_id,$month,$year)
{
  $clsMyDB = new MyDatabase();
  $strCondition2 = "
  SELECT volume_product,volume_product_goal,ab,ab_goal,ab_c,ab_c_goal,datetime FROM
  (
  SELECT * FROM
  (SELECT volume_product as ab ,datetime as dateab FROM `performance` WHERE
   sawId = '$saw_id' and MONTH(datetime)='$month'
   and YEAR(datetime)='$year' AND performance_type = 'AB/Goals'  ORDER BY date(datetime) ASC) as ab
  INNER JOIN
  (SELECT volume_product as volume_product_goal,ab as ab_goal,ab_c as ab_c_goal ,datetime FROM performance_goals WHERE sawId = '$saw_id' and MONTH(datetime)='$month'
   and YEAR(datetime)='$year' ORDER BY date(datetime) ASC) as ab_goal
  ON ab.dateab= ab_goal.datetime
  INNER JOIN
  (SELECT volume_product as volume_product ,datetime as datevo FROM `performance` WHERE
   sawId = '$saw_id' and MONTH(datetime)='$month'
   and YEAR(datetime)='$year' AND performance_type = 'Volume_Product/Goals'  ORDER BY date(datetime) ASC) as vo
  ON vo.datevo= ab_goal.datetime
  INNER JOIN
  (SELECT volume_product as ab_c ,datetime as dateabc FROM `performance` WHERE
   sawId = '$saw_id' and MONTH(datetime)='$month'
   and YEAR(datetime)='$year' AND performance_type = 'Volume_Product/Goals'  ORDER BY date(datetime) ASC) as abc
  ON vo.datevo= abc.dateabc
)
as a";
     $objSelect2 = $clsMyDB->fncSelectRecord($strCondition2);
     if(!$objSelect2)
     {
     $response="no";
     }
     else{
       foreach ($objSelect2 as $value) {
         $response[] =
         [
           'date' => date("d",strtotime($value['datetime'])),
           'volume_product' => $value['volume_product'],
           'volume_product_goal' => $value['volume_product_goal'],
           'ab' => $value['ab'],
           'ab_goal' => $value['ab_goal'],
           'ab_c' => $value['ab_c'],
           'ab_c_goal' => $value['ab_c_goal'],
         ];
       }
     }
       return $response;
}




}//class

?>
