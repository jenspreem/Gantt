//global I need an array of arrays to store a gantt chart
var CHART;
//for convenience an array that will contain the range of days for gantt chart header
var DAYRANGE = [];
//for use with javascript Date objects
var days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];

//to get and show chart data
function showChart(str) 
{
//dont create chart if str empty
if (str==""){return;}

	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.onreadystatechange=function()
	{
	    if (this.readyState==4 && this.status==200)
		{
			CHART=JSON.parse(xhr.responseText);
			getDayrange(str);//synchronous request inside this method so wait before build chart
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
	DAYRANGE.forEach(function(date){headrow.appendChild(createTextElement("td",days[date.getDay()]));});
	table.appendChild(headrow);



//creates rows with active days in colored cells
	for(var i = 0, l = CHART.length; i < l; i++)
	{
		var row=document.createElement("tr");
		row.appendChild(createTextElement("td","X")).className="delcell";
		
		row.appendChild(createTextElement("td","[]"));
		row.appendChild(createTextElement("td",CHART[i][1]));
		row.appendChild(createTextElement("td",CHART[i][2]));
		var taskcell=document.createElement("td");
		//loop for dates
		var startDate = parseDate(CHART[i][3]);
		var endDate = parseDate(CHART[i][4]);
		calcRowDays(row,startDate,endDate);
		table.appendChild(row);

	}

    document.getElementById("ChartArea").replaceChild(table,document.getElementById("ChartArea").childNodes[0]);
	//add createnewtask form to newtaskarea -jq just makes it so much more concise - should use it more
	$("#NewTaskArea").load("newentry.html");
	setDels();

}


//gets dayrange from projects start to finish
function getDayrange(str)
{
	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.onreadystatechange=function()
	{
	    if (this.readyState==4 && this.status==200)
		{
			
			arrayofsingledayarrays=JSON.parse(xhr.responseText);//stupid return format isnt it
			arrayofsingledayarrays.forEach(function(dayarray){DAYRANGE.push(parseDate(dayarray[0]))});//so lets make it better

			return;

		}
	}
//i keep it in synch with other chart data aquisistion cause they are useless without eachother - so async = false here
	xhr.open("GET","getDayrange.php?q="+str,false);
	xhr.send();


}

function addTask()
{
	var Tname = document.forms["NewEntry"]["TaskInput"].value;
	var Tresp = document.forms["NewEntry"]["RespInput"].value;
	var STDate = document.forms["NewEntry"]["StartInput"].value;
	var ENDate = document.forms["NewEntry"]["EndInput"].value;
	var chartID=CHART[0][5];

	if (Tname=="" || Tresp =="" || STDate=="" || ENDate=="") 
	{
//todo modal box or something
		alert("Fill in all fields!");
		document.forms["NewEntry"].reset();
		return;
	}

	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.onreadystatechange=function()
	{
	    if (this.readyState==4 && this.status==200)
		{
			// todo check for successsomehow
			//insert new task to CHART
			var txt=this.responseText;
			var xml = this.responseXML;
			var x=xml.getElementsByTagName("taskid")[0];
			var newid=x.childNodes[0].nodeValue;

			CHART.push([newid,Tname,Tresp,STDate,ENDate,chartID]);

			//add new row to table
			var row=document.createElement("tr");
			row.appendChild(createTextElement("td","X")).className="delcell";
			row.appendChild(createTextElement("td","[]"));
			row.appendChild(createTextElement("td",Tname));
			row.appendChild(createTextElement("td",Tresp));

			calcRowDays(row,parseDate(STDate),parseDate(ENDate));
			document.getElementById("ganttable").appendChild(row);

			//message
			document.getElementById("MessageArea").innerHTML=this.responseText;
			setDels();
			return;
		}
	}

	xhr.open( "POST", "insert_task.php", true );
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.send("task="+encodeURIComponent(Tname)
	+"&person="+encodeURIComponent(Tresp)
	+"&start="+encodeURIComponent(STDate)
	+"&end="+encodeURIComponent(ENDate)
	+"&chid="+encodeURIComponent(chartID)
	); 



}


function remTask(x) 
{
	var rowind = x.parentElement.rowIndex;
	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.onreadystatechange=function()
	{
    	if (this.readyState==4 && this.status==200) 
		{
			document.getElementById("ganttable").deleteRow(rowind);
     		document.getElementById("MessageArea").innerHTML=this.responseText;
		}
	}

 var taskid = CHART[rowind-1][0];
 xhr.open( "POST", "remove_task.php", true );
 xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
 xhr.send( "taskid="+encodeURIComponent(taskid));  



}




//helperfunction for dayrowbuilding

function calcRowDays(row,startDate,endDate)
{

	DAYRANGE.forEach(function(date)
	{
		var celldate = date;
			if (celldate>=startDate && celldate<=endDate)
			{
			row.appendChild(document.createElement("td")).className="bgtd";
			}
			else
			{
			row.appendChild(document.createElement("td"));
			}

	;});


}





//general helperfunctions

function createTextElement(type,txt)
{
	var elem=document.createElement(type);	
    elem.appendChild(document.createTextNode(txt));
	return elem;

}

function parseDate(str)
{

	var dateParts = str.split("-");
	var date = new Date(dateParts[0], (dateParts[1] - 1), dateParts[2]);//js counts months from 0 = january, so mod input
	return date;
}

function setDels(){
	$('.delcell').each(function() {
    $(this).attr('onClick', 'remTask(this);');
	});

}


