<?php 
require_once '../dbinf.php';
require_once 'settings.php'; 
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
$ui =$_POST["ui"];

//validate and/or sanitize
//strings
if (filter_var($task, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES|FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_BACKTICK)==false){die('could not sanitize string error');}
if (filter_var($person, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES|FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_BACKTICK)==false){die('could not sanitize string error');}

//dates
if (!DateTime::createFromFormat('Y-m-d', $start))    {die('Dateformat error');}
if (!DateTime::createFromFormat('Y-m-d', $end))    {die('Dateformat error');}

//ints
if (filter_var($chid, FILTER_VALIDATE_INT)==false)    {die('integer value error');}
if (filter_var($ui, FILTER_VALIDATE_INT)==false)    {die('integer value error');}


#connection to my db
$con = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"gantt");

#sql to insert the row to  table
$sqlin="INSERT INTO tasks ". "(Activity,Person, StartDate, EndDate, chart_id,uid ) ". "VALUES('$task','$person',STR_TO_DATE('$start','%Y-%m-%d'),STR_TO_DATE('$end','%Y-%m-%d'),'$chid','$ui' )";
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



