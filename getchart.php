<?php
#settings
date_default_timezone_set('Europe/Helsinki');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("error_log", "/tmp/php-error.log");
#table identifier
$q = intval($_GET['q']);
#create connection
$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}


$sql="SELECT * FROM user1table1 WHERE chart_id=$q";
//result contains rows from table
$result = mysqli_fetch_all(mysqli_query($con,$sql), MYSQLI_NUM);

echo json_encode($result);



mysqli_close($con);
?>

