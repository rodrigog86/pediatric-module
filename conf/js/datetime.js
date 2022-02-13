var Dayweek=new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
var Months=new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

var date=null;
var day=null;
var month=null;
var year=null;

function print_date() 
{
	date = new Date();
	day =  date.getDate();
	month = date.getMonth(); 
	year = date.getYear(); 
	var Numday = date.getDay();
	if(year<2000) year=year+1900;
	for(intDay=0;intDay<=Dayweek.length;intDay++) if(Numday==intDay) strWeekDay = Dayweek[intDay];
	for(intMonth=0;intMonth<=Months.length;intMonth++) if(month==intMonth) strMonthYear=Months[intMonth];

	return strWeekDay+", "+day+" de "+strMonthYear+" de "+year;
}
function print_time() 
{
	var date = new Date();
	var hours = date.getHours();
	var minutes = fixTime(date.getMinutes());
	var seconds = fixTime(date.getSeconds());

	if(hours<12) formato="a.m.";
	if(hours>=12) formato="p.m.";
	if(hours==0) hours="12";
	if(hours>12) hours=hours-12;
	hours=fixTime(hours);

	//return hours+":"+minutes+":"+seconds+" "+formato;
	return hours+":"+minutes+" "+formato;
}

function fixTime(time) 
{
	if(time<=9) time="0"+time;
	return time;
}
/*
function ShowTime() 
{
	setTimeout("ShowTime()",1000);
	//$("date1").innerHTML=print_date()+" - "+print_time();
}
*/
function getFormatDate()
{
	return year+"-"+fixTime(month+1)+"-"+fixTime(day);
}
