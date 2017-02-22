//global I need an array of arrays to store a gantt chart
var CHART;
var DAYRANGE;


//to get and show chart data
function showChart(str) 
{

	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.onreadystatechange=function()
	{
	    if (this.readyState==4 && this.status==200)
		{
			CHART=JSON.parse(xhr.responseText);
			getProjLimits(str);
			buildChart(str);
			return;

		}
	}
	xhr.open("GET","getchart.php?q="+str,true);
	xhr.send();
}
//builds chart html structure
function buildChart(str)
{

	var table=document.createElement("table");
	table.setAttribute("id", "ganttable");
//todo create a gantt table using start and end dates for each row alongside DAYRANGE
//right now just shows start and end etc. mysql table
	for(var i = 0, l = CHART.length; i < l; i++)
	{
	var row=document.createElement("tr");
		for(var i2 = 0, l2 = CHART[i].length; i2 < l2; i2++)
		{
		var cell=document.createElement("td");
		cell.innerHTML=CHART[i][i2];
		row.appendChild(cell);
		}
	table.appendChild(row);

	}
    document.getElementById("ChartArea").replaceChild(table,document.getElementById("ChartArea").childNodes[0]);


}
//gets dayrange from projects start to finish
function getProjLimits(str)
{
	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.onreadystatechange=function()
	{
	    if (this.readyState==4 && this.status==200)
		{
			DAYRANGE=JSON.parse(xhr.responseText);
			document.getElementById("WarningArea").innerHTML=DAYRANGE;
			return;

		}
	}
	xhr.open("GET","getDayrange.php?q="+str,true);
	xhr.send();


}





