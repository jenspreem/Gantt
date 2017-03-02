<?php

#user identifier
$q = intval($_GET['q']);
#create connection
$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}


$sql="SELECT chart_id,chartname FROM user1table1 WHERE user=$q GROUP BY chart_id";

$result = mysqli_fetch_all(mysqli_query($con,$sql), MYSQLI_NUM);

echo json_encode($result);





mysqli_close($con);
?>

