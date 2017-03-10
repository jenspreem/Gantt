<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php 
require_once '../dbinf.php'; 
#settings
date_default_timezone_set('Europe/Helsinki');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("error_log", "/tmp/php-error.log");
#variables posted
$task =$_POST["task"];
$person =$_POST["person"];
$start =$_POST["start"];
$end =$_POST["end"];
$tasknr =$_POST["taskid"];
$chid =$_POST["chid"];
$ui =$_POST["ui"];

//validate and/or sanitize
//strings
if (filter_var($task, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES|FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_BACKTICK)==false){die('could not sanitize string error');}
if (filter_var($person, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES|FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_BACKTICK)==false){die('could not sanitize string error');}
$task=filter_var($task, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES|FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_BACKTICK);
$person=filter_var($person, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES|FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_BACKTICK);

//dates
if (!DateTime::createFromFormat('Y-m-d', $start))    {die('Dateformat error');}
if (!DateTime::createFromFormat('Y-m-d', $end))    {die('Dateformat error');}

//ints
if (filter_var($chid, FILTER_VALIDATE_INT)==false)    {die('integer value error');}
if (filter_var($ui, FILTER_VALIDATE_INT)==false)    {die('integer value error');}
if (filter_var($tasknr, FILTER_VALIDATE_INT)==false)    {die('integer value error');}


#connection to my db
$con = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"gantt");

#sql to insert the row to  table
$sqlin="UPDATE tasks SET Activity ='$task',Person='$person',StartDate=STR_TO_DATE('$start','%Y-%m-%d'),EndDate=STR_TO_DATE('$end','%Y-%m-%d') WHERE id=$tasknr";




if (mysqli_query($con, $sqlin)) {

	echo "Update successful!";



} else {
    echo "Error: " . $sqlin . "<br>" . mysqli_error($con);
}





mysqli_close($con);
?>

</body>
</html>


