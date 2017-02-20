<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="chartstyle.css">
</head>

<body>

<!-- Create the table to house my ganttable -->
<table id="ganttable">
<tr>
<th></th>
<th>Activity</th>
<th>Responsible</th>

<?php
#settings
date_default_timezone_set('Europe/Helsinki');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("error_log", "/tmp/php-error.log");
#table identifier
$q = intval($_GET['q']);
#create connection
$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"gantt");
$sql="SELECT * FROM user1table1 WHERE chart_id=$q";


#get max range of days

$rangesql="SELECT c.calendar_date FROM calendar c JOIN(SELECT MIN(StartDate) as startday, MAX(EndDate) as endday FROM user1table1 WHERE chart_id=1) u ON c.calendar_date >= u.startday AND c.calendar_date <= u.endday";

$dayarray = mysqli_fetch_all(mysqli_query($con,$rangesql), MYSQLI_NUM);

$result = mysqli_fetch_all(mysqli_query($con,$sql), MYSQLI_NUM);

#create columns for all the days that are needed

foreach($dayarray as $day)
{
echo "<th>" . $day[0] . "</th>";
}
#this day array is kinda retarded but mysqli_fetch_array would only fetch teh first day
#while mysqli_fetch_all will fetch an array of one membered arrays, so make a less retarded dayarray
$smalldayarray=[];

foreach($dayarray as $day)
{
$smalldayarray[] = $day[0] ;
}

echo "</tr>";

#next print rows for different tasks
foreach($result as $row) {
echo "<tr>";
echo '<td class="tooltip" onclick="remTask(this)"> X <span class="deletetext">click to delete task</span></td>';
echo "<td>" . $row[1] . "</td>";
echo "<td>" . $row[2] . "</td>";

#color only these cells that represent activity (from start till end) others stay just empty
  for($i=0;$i<count($dayarray);$i++){
    if (strtotime($smalldayarray[$i])>=strtotime($row[3]) and strtotime($smalldayarray[$i]) <= strtotime($row[4])) {
      echo <<<EOL
    <td class="bgtd"></td>
EOL;
    } else {
      echo "<td>".""."</td>";
    }

}


echo "</tr>";
}


mysqli_close($con);
?>

</table>
<!-- after creating  the table I'll also create the form for adding new tasks into it -->
<form name="NewEntry" >
  <input type="text" name="TaskInput" placeholder="Task" size="12">
<input type="text" name="RespInput" placeholder="Person" size="12">
<input type="text" name="StartInput" placeholder="StartDate"class="datepicker">
<input type="text" name="EndInput" placeholder="EndDate" class="datepicker">
<input type="button" value="Create" onclick="addTask()">
</form>




</body>
</html>
