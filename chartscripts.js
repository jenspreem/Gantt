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
//create first row in table
	var headrow = document.createElement("tr");
	headrow.appendChild(createTextElement("td","X"));
	headrow.appendChild(createTextElement("td","[]"));
	headrow.appendChild(createTextElement("td","Task"));
	headrow.appendChild(createTextElement("td","Responsible"));
	//first row needs dayrange i use foreach for brevity - if efficiency needed? replace with for loop
	DAYRANGE.forEach(function(datetxt){headrow.appendChild(createTextElement("td",datetxt));});
	table.appendChild(headrow);

//todo create a gantt table using start and end dates for each row alongside DAYRANGE
//right now just shows start and end etc. mysql table
	for(var i = 0, l = CHART.length; i < l; i++)
	{
	var row=document.createElement("tr");
	row.appendChild(createTextElement("td","X"));
	row.appendChild(createTextElement("td","[]"));

	var taskcell=document.createElement("td");
	taskcell.innerHTML=CHART[i][1];
	var resp_cell=document.createElement("td");
	resp_cell.innerHTML=CHART[i][2];
	table.appendChild(row);
	row.appendChild(taskcell);
	row.appendChild(resp_cell);

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
			return;

		}
	}
//i keep it in synch with other chart data aquisistion cause they are useless without eachother - so async = false here
	xhr.open("GET","getDayrange.php?q="+str,false);
	xhr.send();


}

function createTextElement(type,txt){
	var elem=document.createElement(type);	
    elem.appendChild(document.createTextNode(txt));
	return elem;

}




