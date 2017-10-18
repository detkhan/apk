<?php
require_once("class/database.php");
class sawmill
{

  function cutshortname($branch)
  {
  $clsMyDB = new MyDatabase();
  $rawdata=explode(',',$branch);
  foreach ($rawdata as $value) {
    $data=$this->getdata($value);
    $saw_id=$data[0]['sawId'];
    $response[] =['sawId' => $saw_id,'shortname'=>$value];
  }
  return $response;
  }//function

function getdata($shortname)
{
$clsMyDB = new MyDatabase();
$strCondition2 = "SELECT  *  FROM  sawmill  WHERE  `shortname` ='$shortname'";
$objSelect2 = $clsMyDB->fncSelectRecord($strCondition2);
if(!$objSelect2)
{
$response="no";
}
else{
$response=$objSelect2;
}
return $response;
}//function

}//class

?>
