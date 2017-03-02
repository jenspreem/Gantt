//global I need an array of arrays to store a gantt chart
var CHART;
//an array that will contain the range of days for gantt chart header, we get it from server
var DAYRANGE = [];
//for use with javascript Date objects
var days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
//lets store current chart id here
var curChartID;

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
			curChartID=str;
			drawChart();
			return;

		}
	}
	xhr.open("GET","getchart.php?q="+str,true);
	xhr.send();
}



//builds HTML chart ("ganttable") from CHART adds it to ChartArea
function drawChart()
{
//it shifted day early
console.log("bef drawchart");
console.log(DAYRANGE);
	var table=document.createElement("table");
	table.setAttribute("id", "ganttable");
	//create first row in table
	var headrow = document.createElement("tr");
	headrow.appendChild(createTextElement("td","X"));
	headrow.appendChild(createTextElement("td","[]"));
	headrow.appendChild(createTextElement("td","Task"));
	headrow.appendChild(createTextElement("td","Responsible"));
	//first row needs dayrange
	DAYRANGE.forEach(function(date){headrow.appendChild(createTextElement("td",datestring(date)));});
	table.appendChild(headrow);
	//creates rows with active days in colored cells
	for(var i = 0, l = CHART.length; i < l; i++)
	{
		var row=document.createElement("tr");
		row.appendChild(createTextElement("td","X")).className="delcell";	
		row.appendChild(createTextElement("td","[]")).className="modcell";
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
	setMods();

}


//gets dayrange from projects start to finish
//todo:callback solution with getchart
function getDayrange(str)
{
	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.onreadystatechange=function()
	{
	    if (this.readyState==4 && this.status==200)
		{
			//empty it before filling
			DAYRANGE=[];
			arrayofsingledayarrays=JSON.parse(xhr.responseText);//stupid return format isnt it
			arrayofsingledayarrays.forEach(function(dayarray){DAYRANGE.push(parseDate(dayarray[0]))});//so lets make it better
			return;
		}
	}
	//i keep it in synch with other chart data aquisistion cause they are useless without eachother - so async = false here
	xhr.open("GET","getDayrange.php?q="+str,false);
	xhr.send();

}

//todo:input sanitation and exception
//todo:modal boxes for alerts
function pre_addTask()
{
	var Tname = document.forms["NewEntry"]["TaskInput"].value;
	var Tresp = document.forms["NewEntry"]["RespInput"].value;
	var STDate = document.forms["NewEntry"]["StartInput"].value;
	var ENDate = document.forms["NewEntry"]["EndInput"].value;

console.log("bef pre_addTask");
console.log(STDate);
console.log(ENDate);
console.log(parseDate(STDate));
console.log(parseDate(ENDate));



	if (Tname=="" || Tresp =="" || STDate=="" || ENDate=="") 
	{
		alert("Fill in all fields!");
		document.forms["NewEntry"].reset();
		return;
	}
	if (parseDate(STDate)>parseDate(ENDate)) 
	{
		alert("Start Date cannot be later than End Date");
		document.forms["NewEntry"].reset();
		return;
	}
//if start or end date is outside current dayrange
	if (parseDate(STDate)< DAYRANGE[0] || parseDate(STDate)> DAYRANGE[DAYRANGE.length-1] || parseDate(ENDate)> DAYRANGE[DAYRANGE.length-1] || parseDate(ENDate)< DAYRANGE[0])
	{
	//addExtTask gets new dayrange,and calls addTask with applicable parameters
	extendDayrange(Tname,Tresp,STDate,ENDate,addTask);

	return;
	}
	addTask(Tname,Tresp,STDate,ENDate);

}


function addTask(t,r,s,e)
{
	var Tname = t;
	var Tresp = r;
	var STDate = s;
	var ENDate = e;
	var chartID=curChartID;

console.log("bef addTask");
console.log(STDate);
console.log(ENDate);
console.log(parseDate(STDate));
console.log(parseDate(ENDate));

	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.onreadystatechange=function()
	{
	    if (this.readyState==4 && this.status==200)
		{
			//insert new task to CHART using new id
			var xml = this.responseXML;
			var x=xml.getElementsByTagName("taskid")[0];
			var newid=x.childNodes[0].nodeValue;
			CHART.push([newid,Tname,Tresp,STDate,ENDate,chartID]);
			drawChart();
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

	if (confirm("Do You really want to delete this task?"))
	{
		var rowind = x.parentElement.rowIndex;
		var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
		var taskid = CHART[rowind-1][0];
		xhr.onreadystatechange=function()
		{
	    	if (this.readyState==4 && this.status==200) 
			{

				//remove the task from local CHART 
	   			CHART.splice(rowind-1, 1);
				//new dayrange
//todo  -- later make only call to server if dayrange changes check
				getDayrange(curChartID);
				drawChart();			
	
			}
		}	
		xhr.open( "POST", "remove_task.php", true );
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send( "taskid="+encodeURIComponent(taskid));  
	}
	else {return;}

}


//	todo: guarantee there will be only one updatebox at all times
function openModForm(x) 
{

	var updatebox = document.createElement("div");
	updatebox.id = 'updatebox';
	
	//prevent click propagation
	updatebox.addEventListener("click", stopEvent, false);
	x.appendChild(updatebox);
	//load form to div
    $("#updatebox").load("modform.html", function(){
	//populate modform with default values
	var rowind = x.parentElement.rowIndex;
	var task = CHART[rowind-1][1];
	var person = CHART[rowind-1][2];
	var start = CHART[rowind-1][3];
	var end  = CHART[rowind-1][4];
	document.forms["UpdateForm"]["TaskInput"].value=task;
	document.forms["UpdateForm"]["RespInput"].value=person;
	document.forms["UpdateForm"]["StartInput"].value=start;
	document.forms["UpdateForm"]["EndInput"].value=end;
    });

}

function pre_modTask()
{
	//these come from form inside updatebox
	var task = document.forms["UpdateForm"]["TaskInput"].value;
	var person = document.forms["UpdateForm"]["RespInput"].value;
	var start = document.forms["UpdateForm"]["StartInput"].value;
	var end  = document.forms["UpdateForm"]["EndInput"].value;

console.log("bef pre_modTask");
console.log(STDate);
console.log(ENDate);
console.log(parseDate(STDate));
console.log(parseDate(ENDate))

	if (parseDate(STDate)>parseDate(ENDate)) 
	{
		alert("Start Date cannot be later than End Date");
//todo close  modform and open new one openmodfrom()
		return;
	}
//if start or end date is outside current dayrange
	if (parseDate(STDate)< DAYRANGE[0] || parseDate(STDate)> DAYRANGE[DAYRANGE.length-1] || parseDate(ENDate)> DAYRANGE[DAYRANGE.length-1] || parseDate(ENDate)< DAYRANGE[0])
	{
	//addExtTask gets new dayrange,and calls addTask with applicable parameters
	extendDayrange(Tname,Tresp,STDate,ENDate,modTask);

	return;
	}
	modTask(Tname,Tresp,STDate,ENDate);



}






function modTask(t,r,s,e)
{
	var Tname = t;
	var Tresp = r;
	var STDate = s;
	var ENDate = e;
	var chartID=curChartID;
	var curRow=document.getElementById("updatebox").parentElement.parentElement;
	var rowind = curRow.rowIndex;
	var taskid = CHART[rowind-1][0];

	if (parseDate(start)>parseDate(end)) 
	{
	//todo modal box or something
		alert("Start Date cannot be later than End Date");
		document.forms["NewEntry"].reset();
		return;
	}
	//todo if start or end date is larger than current dayrange you should expand dayrange
	if (parseDate(start)< DAYRANGE[0] || parseDate(end)> DAYRANGE[DAYRANGE.length-1])
	{
	//get from server a new dayrange?
	}

	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.onreadystatechange=function()
	{
    	if (this.readyState==4 && this.status==200) 
		{

     		document.getElementById("MessageArea").innerHTML=this.responseText;
			//modify CHART
			CHART[rowind-1][1]=task;
			CHART[rowind-1][2]=person;
			CHART[rowind-1][3]=start;
			CHART[rowind-1][4]=end;
			//new dayrange
			//lets removeupdatebox from modcell after work is done
     		var p=document.getElementById("updatebox").parentElement;
			p.removeChild(p.childNodes[1]);
			drawChart();
		}
	}


	xhr.open( "POST", "mod_task.php", true );
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.send( "taskid="+encodeURIComponent(taskid)
	+"&task="+encodeURIComponent(task)
	+"&person="+encodeURIComponent(person)
	+"&start="+encodeURIComponent(start)
	+"&end="+encodeURIComponent(end)
	+"&chid="+encodeURIComponent(chartID)
	);  


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



function extendDayrange(t,r,st,en,funct_mod_add)
{

//parsedate is somehow fucking shit up here?
console.log("bef extend");
console.log(st);
console.log(en);
console.log(parseDate(st));
console.log(parseDate(en));


	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.onreadystatechange=function()
	{
    	if (this.readyState==4 && this.status==200) 
		{
			//empty it before filling
			DAYRANGE=[];
			arrayofsingledayarrays=JSON.parse(xhr.responseText);
			arrayofsingledayarrays.forEach(function(dayarray){DAYRANGE.push(parseDate(dayarray[0]))});
			funct_mod_add(t,r,st,en);
			return;
		}
	}

	xhr.open( "POST", "get_extended_range.php", true );
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//range from earliest date to latest
	var dates=[];
	dates.push(parseDate(st));
	dates.push(parseDate(en));
	dates.push(DAYRANGE[0]);
	dates.push(DAYRANGE[DAYRANGE.length-1]);
	dates.sort(date_sort_asc);
console.log(dates);
	xhr.send("start="+encodeURIComponent(datestring(dates[0]))+"&end="+encodeURIComponent(datestring(dates[dates.length-1])));  



}


//general helperfunctions

function createTextElement(type,txt)
{
	var elem=document.createElement(type);	
    elem.appendChild(document.createTextNode(txt));
	return elem;

}

function date_sort_asc(date1, date2) {
  if (date1 > date2) return 1;
  if (date1 < date2) return -1;
  return 0;
};

function parseDate(str)
{

	var dateParts = str.split("-");
	var date = new Date(dateParts[0], (dateParts[1] - 1), dateParts[2]);//js counts months from 0 = january, so mod input
	return date;
}


function datestring(date) {
  var mm = date.getMonth() + 1; // /js counts months from 0
  var dd = date.getDate();

  return [date.getFullYear(),
          (mm>9 ? '' : '0') + mm,
          (dd>9 ? '' : '0') + dd
         ].join('-');
};



function setDels(){
	$('.delcell').each(function() {
    $(this).attr('onClick', 'remTask(this);');
	});

}

function setMods(){
	$('.modcell').each(function() {
    $(this).attr('onClick', 'openModForm(this);');
	});

}



function stopEvent(ev) 
{
// this ought to keep parent from getting the clicks-n-stuff
	ev.stopPropagation();
}




