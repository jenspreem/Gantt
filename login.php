<?php
header('Content-Type', 'application/json');
#settings
date_default_timezone_set('Europe/Helsinki');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("error_log", "/tmp/php-error.log");
#variables posted
$uname =$_POST["uname"];
$pw =$_POST["pw"];

#connection to my db
$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"gantt");

#sql get hashed pw applicable to uname
$sql="SELECT pw FROM users WHERE id=$uname";


#commence sql and compare result with my pw hash
$result = mysqli_fetch_array(mysqli_query($con,$sql), MYSQLI_NUM);


if (password_verify($pw, $result[0])){echo '{"success": true}' ;}
else {echo '{"success": false}';}



mysqli_close($con);
?>




