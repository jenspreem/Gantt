<?php 
require_once '../dbinf.php';
require_once 'settings.php'; 

#table identifier
$q = intval($_GET['q']);
#create connection
$con = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

if (filter_var($q, FILTER_VALIDATE_INT)==false)    {die('integer value error');}

$sql="SELECT * FROM tasks WHERE chart_id=$q";
//result contains rows from table
$result = mysqli_fetch_all(mysqli_query($con,$sql), MYSQLI_NUM);

echo json_encode($result);





mysqli_close($con);
?>

