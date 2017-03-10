<?php 
require_once '../dbinf.php';
require_once 'settings.php'; 
header('Content-Type', 'application/json');

#variables posted
$uname =$_POST["uname"];
$pw =$_POST["pw"];

if (filter_var($uname, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES|FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_BACKTICK)==false){die('could not sanitize string error');}
$uname=filter_var($uname, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES|FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_BACKTICK);

if (filter_var($pw, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES|FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_BACKTICK)==false){die('could not sanitize string error');}
$pw=filter_var($pw, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES|FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_BACKTICK);




#connection to my db

$con = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"gantt");

#sql get hashed pw applicable to uname
$sql="SELECT pw,id FROM users WHERE name='$uname'";


#commence sql and compare result with my pw hash
$result = mysqli_fetch_array(mysqli_query($con,$sql), MYSQLI_NUM);


if (password_verify($pw, $result[0]))
{
	$response=array
	("success" => true,
	 "id" => $result[1],
	);
	echo json_encode($response) ;
}
else {echo '{"success": false}';}



mysqli_close($con);
?>




