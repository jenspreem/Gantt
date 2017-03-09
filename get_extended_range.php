<?php
#settings
date_default_timezone_set('Europe/Helsinki');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("error_log", "/tmp/php-error.log");


$start =$_POST["start"];
$end =$_POST["end"];

#create connection
$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

if (!DateTime::createFromFormat('Y-m-d', $start))    {die('Dateformat error');}
if (!DateTime::createFromFormat('Y-m-d', $end))    {die('Dateformat error');}


$rangesql="SELECT calendar_date FROM calendar WHERE calendar_date >= STR_TO_DATE('$start','%Y-%m-%d') AND calendar_date <= STR_TO_DATE('$end','%Y-%m-%d')";
$dayarray = mysqli_fetch_all(mysqli_query($con,$rangesql), MYSQLI_NUM);

echo json_encode($dayarray);





mysqli_close($con);
?>

