<?php

#user identifier
$q = intval($_GET['q']);
#create connection
$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}


$sql="SELECT id,name FROM charts WHERE uid=$q";
$qry=mysqli_query($con,$sql);
if ($qry==false){echo mysqli_error($con);}

$result = mysqli_fetch_all($qry, MYSQLI_NUM);

echo json_encode($result);





mysqli_close($con);
?>
