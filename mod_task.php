<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
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
#connection to my db
$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"gantt");

#sql to insert the row to  table
$sqlin="UPDATE user1table1 SET Activity ='$task',Person='$person',StartDate=STR_TO_DATE('$start','%m/%d/%Y'),EndDate=STR_TO_DATE('$end','%m/%d/%Y') WHERE id=$tasknr AND chart_id=1";




if (mysqli_query($con, $sqlin)) {

	echo "Update successful!";



} else {
    echo "Error: " . $sqlin . "<br>" . mysqli_error($con);
}





mysqli_close($con);
?>

</body>
</html>


