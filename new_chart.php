<?php 
require_once '../dbinf.php';
require_once 'settings.php'; 
header('Content-Type: text/xml');

//name of new chart


$chname =$_POST["chname"];
$ui =$_POST["ui"];

if (filter_var($chname, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES|FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_BACKTICK)==false){die('could not sanitize string error');}
$chname =filter_var($chname, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES|FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_BACKTICK);

if (filter_var($ui, FILTER_VALIDATE_INT)==false)    {die('integer value error');}



#connection to my db
$con = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"gantt");

#sql to insert the row to  table
$sqlin="INSERT INTO charts ". "(name,uid) ". "VALUES('$chname','$ui')";
$sqlout="SELECT LAST_INSERT_ID()";



if (mysqli_query($con, $sqlin)) {

	$result = mysqli_fetch_array(mysqli_query($con,$sqlout), MYSQLI_NUM);
	echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
	echo "<CHID>$result[0]</CHID>";




} else {
    echo "Error: " . $sqlin . "<br>" . mysqli_error($con);

}





mysqli_close($con);
?>

