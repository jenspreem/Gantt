<?php 
require_once '../dbinf.php'; 

#user identifier
$q = intval($_GET['q']);
#create connection


$con = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

if (filter_var($q, FILTER_VALIDATE_INT)==false)    {die('integer value error');}

$sql="SELECT id,name FROM charts WHERE uid=$q";
$qry=mysqli_query($con,$sql);
if ($qry==false){echo mysqli_error($con);}

$result = mysqli_fetch_all($qry, MYSQLI_NUM);

echo json_encode($result);





mysqli_close($con);
?>

