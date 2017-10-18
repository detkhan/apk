<?php
require_once("class/database.php");
class user
{

public function login($email,$password)
{
  $clsMyDB = new MyDatabase();
  $strCondition2 = "SELECT  *  FROM  users WHERE  `email` ='$email' and `password` =sha1('$password')";
  $objSelect2 = $clsMyDB->fncSelectRecord($strCondition2);
  if(!$objSelect2)
  {
  $objdata="no";
  }
  else{
  $objdata=$objSelect2;
  }
  return $objdata;
}//function login


public function getprofile($email)
{
$clsMyDB = new MyDatabase();
$strCondition2 = "SELECT  *  FROM  users WHERE  `email` ='$email'";
$objSelect2 = $clsMyDB->fncSelectRecord($strCondition2);
if(!$objSelect2)
{
$objdata="no";
}
else{
$objdata=$objSelect2;
}
return $objdata;
}






}//class

?>
