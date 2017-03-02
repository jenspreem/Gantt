<?php
header('Content-Type: text/xml');
#settings
date_default_timezone_set('Europe/Helsinki');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("error_log", "/tmp/php-error.log");
#variables posted
$task =$_POST["task"];
$person =$_POST["person"];
$start =$_POST["start"];
$end =$_POST["end"];
$chid =$_POST["chid"];
$chname =$_POST["chname"];
$ui =$_POST["ui"];
#connection to my db
$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"gantt");

#sql to insert the row to  table
$sqlin="INSERT INTO user1table1 ". "(Activity,Person, StartDate, EndDate, chart_id,chartname,user ) ". "VALUES('$task','$person',STR_TO_DATE('$start','%Y-%m-%d'),STR_TO_DATE('$end','%Y-%m-%d'),'$chid','$chname','$ui' )";
$sqlout="SELECT LAST_INSERT_ID()";



if (mysqli_query($con, $sqlin)) {
	$result = mysqli_fetch_array(mysqli_query($con,$sqlout), MYSQLI_NUM);
	echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
	echo "<taskid>$result[0]</taskid>";




} else {
    echo "Error: " . $sqlin . "<br>" . mysqli_error($con);

}





mysqli_close($con);
?>



