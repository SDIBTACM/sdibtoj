
function changestatu(id,to,cid,usr_id){
	if (id.length==0)
  	{ 
  		document.getElementById(id).innerHTML="";
  		return;
  	}
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
  	alert ("Browser does not support HTTP Request");
  	return;
	} 
	var url="./getnewstatus.php";
	url=url+"?to="+to+"&cid="+cid+"&user="+usr_id;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=function(){stateChanged(id)};
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
} 
var xmlHttp;
function GetXmlHttpObject()
{
	xmlHttp=null;
	try
 	{
 		xmlHttp=new XMLHttpRequest();
 	}
	catch (e)
 	{
 		try{
  		xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  		}
 		catch (e){
  		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  		}
 	}
	return xmlHttp;
}
function stateChanged(id) 
{ 
	if (xmlHttp.readyState==4 && xmlHttp.status==200)
 	{ 
 		document.getElementById(id).innerHTML=xmlHttp.responseText;
 	} 
}
