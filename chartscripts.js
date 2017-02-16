//to show  chart
function showChart(str) 
{
  if (str=="") {
    document.getElementById("ChartArea").innerHTML='<b>No activity selected</b>';
    return;
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("ChartArea").innerHTML=this.responseText;
//you need the datepicker also in this newly created element
      $( ".datepicker" ).datepicker();
    }
  }
  xmlhttp.open("GET","getchart.php?q="+str,true);
  xmlhttp.send();
}

function addTask() 
{

  var Tname = document.forms["NewEntry"]["TaskInput"].value;
  var Tresp = document.forms["NewEntry"]["RespInput"].value;
  var STDate = document.forms["NewEntry"]["StartInput"].value;
  var ENDate = document.forms["NewEntry"]["EndInput"].value;

  if (Tname=="" || Tresp =="" || STDate=="" || ENDate=="") 
  {
    document.getElementById("WarningArea").innerHTML='<b>Fill all fields!</b>';
    document.forms["NewEntry"].reset();
    return;
  }
  if (window.XMLHttpRequest)
  {
    xmlhttp2=new XMLHttpRequest();
  }   else 
     { 
     xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
     }

  xmlhttp2.onreadystatechange=function() 
  {
    if (this.readyState==4 && this.status==200) 
   {
      document.getElementById("WarningArea").innerHTML=this.responseText;

     var table = document.getElementById("ganttable");
     var row = table.insertRow(-1);
     var cell1 = row.insertCell(-1);
     var cell2 = row.insertCell(-1);
     cell1.innerHTML = Tname;
     cell2.innerHTML = Tresp;
//so how to add colored cells for dates?

    }
  }

  xmlhttp2.open( "POST", "insert_task.php", true );
  xmlhttp2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xmlhttp2.send( "task="+encodeURIComponent(Tname)+
              "&person="+encodeURIComponent(Tresp)+
              "&start="+encodeURIComponent(STDate)+
              "&end="+encodeURIComponent(ENDate) );  

}




function calcRow() 
{
//get first rows first and last cells they contain the project start and end-dates 

     var projstart = document.getElementById("ganttable").rows[0].cells[3].innerHTML;
     var projend = document.getElementById("ganttable").rows[0].cells[-1].innerHTML;
//todo --- you should also set a check that you can-not easily add tasks that are bigger than the projects critical path?

//todo find the indexes for colored cells

//todo construct row

}

function DifferenceInDays(firstDate, secondDate)
{
  return Math.round((secondDate-firstDate)/(1000*60*60*24));
}



