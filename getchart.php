<!DOCTYPE html>
<html>
<head>



<style>
table {
    width: 100%;
    border-collapse: collapse;
    display: block;
    overflow-x: auto;


}

table, td, th {
    border: 1px solid black;
    padding: 5px;


}

th {text-align: left;}

.bgtd {
   
    background-color: red;
   
}

input {
 display:inline;
float:left;
    margin:5px;   
}


</style>



</head>
<body>

<?php

date_default_timezone_set('Europe/Helsinki');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("error_log", "/tmp/php-error.log");

$q = intval($_GET['q']);

$con = mysqli_connect('localhost','ganttuser1','pw1','gantt');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"gantt");
$sql="SELECT * FROM user1table1 WHERE chart_id=$q";


#getrange 

$rangesql="SELECT c.calendar_date FROM calendar c JOIN(SELECT MIN(StartDate) as startday, MAX(EndDate) as endday FROM user1table1 WHERE chart_id=1) u ON c.calendar_date >= u.startday AND c.calendar_date <= u.endday";

$dayarray = mysqli_fetch_all(mysqli_query($con,$rangesql), MYSQLI_NUM);
#why my dayarray is empty for fuck sakes

if (!$dayarray){echo "<p>wtf</p>";}

$result = mysqli_fetch_all(mysqli_query($con,$sql), MYSQLI_NUM);

#make first row
echo "<table>
<tr>
<th>Activity</th>
<th>Responsible</th>";


#replace these with rangeloop

foreach($dayarray as $day)
{
echo "<th>" . $day[0] . "</th>";
}
#this day array is kinda retarded but mysqli_fetch_array would only fetch teh first day
#while mysqli_fetch_all will fetch ana rray of arrays of one members, so make a less retarded dayarray
$smalldayarray=[];

foreach($dayarray as $day)
{
$smalldayarray[] = $day[0] ;
}

echo "</tr>";

#next rows
foreach($result as $row) {
echo "<tr>";
echo "<td>" . $row[1] . "</td>";
echo "<td>" . $row[2] . "</td>";

#todo need ruudud mis j22vad start ja end day vahele vastaval activityl punasega teha?
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




#echo "</table>";
mysqli_close($con);
?>

<tr>
<td colspan="6"> 
<form name="NewEntry" >
  <input type="text" name="TaskInput" placeholder="Task" size="12">
<input type="text" name="RespInput" placeholder="Person" size="12">
<input type="text" name="StartInput" placeholder="StartDate"class="datepicker">
<input type="text" name="EndInput" placeholder="EndDate" class="datepicker">
<input type="button" value="Create" onclick="addTask()">
</form>
</td>
</tr>
</table>


</body>
</html>
