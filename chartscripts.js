//global I need an array of arrays to store a gantt chart
var CHART;

//to get chart data
function showChart(str) 
{

	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.onreadystatechange=function()
	{
	    if (this.readyState==4 && this.status==200)
		{
			CHART=JSON.parse(xhr.responseText);
			buildChart(str);
			return;

		}
	}
	xhr.open("GET","getchart.php?q="+str,true);
	xhr.send();
}

function buildChart(str)
{

	var table=document.createElement("table");
	table.setAttribute("id", "ganttable");
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




