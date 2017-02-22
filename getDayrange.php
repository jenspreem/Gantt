<?php

$q = intval($_GET['q']);
#create connection
$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

$rangesql="SELECT c.calendar_date FROM calendar c JOIN(SELECT MIN(StartDate) as startday, MAX(EndDate) as endday FROM user1table1 WHERE chart_id=$q) u ON c.calendar_date >= u.startday AND c.calendar_date <= u.endday";
$dayarray = mysqli_fetch_all(mysqli_query($con,$rangesql), MYSQLI_NUM);

echo json_encode($dayarray);





mysqli_close($con);
?>







