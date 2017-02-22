<?php

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

