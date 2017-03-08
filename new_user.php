<?php
//https://github.com/paragonie/random_compat/blob/master/README.md
//rand_int 5.6 library


try {
    require 'random_compat.phar';
} catch (Exception $e) {
    exit('Require failed! Error: '.$e);

}


$uname =$_POST["uname"];
$mail =$_POST["mail"];

$pw=random_str(6);
$hash=password_hash($pw,PASSWORD_BCRYPT);
$sqlin="INSERT INTO users (name,pw,mail) VALUES('$uname','$hash','$mail')";

#connection to my db
$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"gantt");



if (mysqli_query($con, $sqlin))
{
	$response=array
	(
    "status" => "success",
    "message"   => "password sent to specified email",
	);
	$message="you can login in gantapp as $uname : $pw";

	mail($mail, 'gantapp registration', $message);
echo json_encode($response);
} 
else {
   	$response=array
	(
    "status" => "failure",
    "message"   => mysqli_error($con),
	);
echo json_encode($response);

}






function random_str(
    $length,
    $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
) {
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    if ($max < 1) {
        throw new Exception('$keyspace must be at least two characters long');
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

?>


