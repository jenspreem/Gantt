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
#connection to my db
$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"gantt");

#sql to insert the row to  table
$sqlin="INSERT INTO user1table1 ". "(Activity,Person, StartDate, EndDate, chart_id ) ". "VALUES('$task','$person',STR_TO_DATE('$start','%m/%d/%Y'),STR_TO_DATE('$end','%m/%d/%Y'),1 )";




if (mysqli_query($con, $sqlin)) {

	echo "Record added successfully!";



} else {
    echo "Error: " . $sqlin . "<br>" . mysqli_error($con);
}





mysqli_close($con);
?>

</body>
</html>


