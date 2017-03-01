<?php


$start =$_POST["start"];
$end =$_POST["end"];

#create connection
$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

$rangesql="SELECT calendar_date FROM calendar WHERE calendar_date >= STR_TO_DATE('$start','%Y-%m-%d') AND calendar_date <= STR_TO_DATE('$end','%Y-%m-%d')";
$dayarray = mysqli_fetch_all(mysqli_query($con,$rangesql), MYSQLI_NUM);

echo json_encode($dayarray);





mysqli_close($con);
?>

