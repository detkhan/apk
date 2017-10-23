<?php
include("class/user.php");
include("class/report.php");
include("class/sawmill.php");
$email=$_GET['email'];
$datereport=$_GET['datereport'];
$ob_user= new user;
$ob_sawmill= new sawmill;
$ob_report= new report;
$get_user=$ob_user->getprofile($email);
$branch=$get_user[0]['branch'];
$data_shortname=$ob_sawmill->cutshortname("$branch");
//var_dump($data_shortname);
foreach ($data_shortname as $value) {
$response[]=$ob_report->reportRealTime($value['sawId'],$value['shortname'],$datereport);
}
$data= json_encode($response);
echo $data;
//var_dump($response);
