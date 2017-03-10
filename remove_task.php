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
$taskid =$_POST["taskid"];


if (filter_var($taskid, FILTER_VALIDATE_INT)==false)    {die('integer value error');}

#connection to my db
$con = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"gantt");

#sql to insert the row to  table
$sql="DELETE FROM tasks WHERE id=$taskid";


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
