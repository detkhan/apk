<?php
include("class/user.php");
include("class/sawmill.php");
$email=$_GET['email'];
$ob_user= new user;
$ob_sawmill= new sawmill;
$get_user=$ob_user->getprofile($email);
$branch=$get_user[0]['branch'];
$data_shortname=$ob_sawmill->cutshortname("$branch");
foreach ($data_shortname as $value) {
$response[]=[
'sawId' => $value['sawId'],
'shortname' => $value['shortname']
];
}
$data= json_encode($response);
echo $data;
