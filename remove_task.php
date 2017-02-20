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
$taskid =$_POST["taskid"];

#connection to my db
$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"gantt");

#sql to insert the row to  table
$sql="DELETE FROM user1table1 WHERE id=$taskid";


#commence sql and get verification

if (mysqli_query($con, $sql)) {
    echo "Task deleted successfully";

} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($con);
}

mysqli_close($con);
?>

</body>
</html>
