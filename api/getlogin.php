<?php
include("class/user.php");
$email=$_GET['email'];
$password=$_GET['password'];
$ob_user= new user;
$get_user=$ob_user->login($email,$password);
if ($get_user!="no") {
  $response[]=
  [
    'fullname' => $get_user[0]['firstname']."  ".$get_user[0]['lastname'],
    'type' => $get_user[0]['type'],
    'status' => "yes"
  ];
}else{
  $response[]=
  [
    'fullname' => "no",
    'type' => "no",
    'status' => "no"
  ];
}

$data= json_encode($response);
echo $data;
//var_dump($response);
