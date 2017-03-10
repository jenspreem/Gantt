<?php 
require_once '../dbinf.php'; 
#settings
date_default_timezone_set('Europe/Helsinki');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("error_log", "/tmp/php-error.log");

//chartid
$q = intval($_GET['q']);
#create connection
$con = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

if (filter_var($q, FILTER_VALIDATE_INT)==false)    {die('integer value error');}


$rangesql="SELECT c.calendar_date FROM calendar c JOIN(SELECT MIN(StartDate) as startday, MAX(EndDate) as endday FROM tasks WHERE chart_id=$q) u ON c.calendar_date >= u.startday AND c.calendar_date <= u.endday";
$dayarray = mysqli_fetch_all(mysqli_query($con,$rangesql), MYSQLI_NUM);

echo json_encode($dayarray);





mysqli_close($con);
?>







