<?php
header('Content-Type: text/xml');
#settings
date_default_timezone_set('Europe/Helsinki');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("error_log", "/tmp/php-error.log");

//name of new chart


$chname =$_POST["chname"];
$ui =$_POST["ui"];
#connection to my db
$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"gantt");

#sql to insert the row to  table
$sqlin="INSERT INTO charts ". "(name,uid) ". "VALUES('$chname','$ui')";




if (mysqli_query($con, $sqlin)) {

	echo "New chart created, select it from drop down list";




} else {
    echo "Error: " . $sqlin . "<br>" . mysqli_error($con);

}





mysqli_close($con);
?>

