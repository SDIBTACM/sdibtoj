var xmlHttp;
function addquestion(spanid,examid,quesid,typeid)
{
	xmlHttp=GetXmlHttpObject();
	if(xmlHttp==null){
		alert("Sorry,Your Browser does not support HTTP Request");
		return;
	}
	var url="exam_problem_ok.php";
	url=url+"?eid="+examid+"&quesid="+quesid+"&type="+typeid;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=function(){stateChanged(spanid)};
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

/*function submitcode(spanid,codeid,languageid,pid,examid)
{
	var source=document.getElementById(codeid).value;
	var language=document.getElementById(languageid).value;
	var str="source="+source+"&language="+language+"&id="+pid+"&eid="+examid;
	var flag=mychecksource(source,language);
	if(flag){
		xmlHttp=GetXmlHttpObject();
		if(xmlHttp==null){
			alert("Sorry,Your Browser does not support HTTP Request");
			return;
		}
		var url="programsubmit.php";
		xmlHttp.onreadystatechange=function(){stateChanged(spanid)};
		xmlHttp.open("POST",url,true);
		xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=UTF-8");
		xmlHttp.send(str);
	}
}
*/
function updateresult(spanid,pid,examid)
{
	xmlHttp=GetXmlHttpObject();
	if(xmlHttp==null){
		alert("Sorry,Your Browser does not support HTTP Request");
		return;
	}
	var url="updateresult.php";
	url=url+"?id="+pid+"&eid="+examid;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=function(){stateChanged(spanid)};
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChanged(spanid)
{
	if(xmlHttp.readyState==4 && xmlHttp.status==200)
 	{
 		document.getElementById(spanid).innerHTML=xmlHttp.responseText;
 	}
}

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

function mychecksource(src,language)
{
        if (language>"3")
            return true;
		var keys=new Array();
		var errs=new Array();
		var msg="";
		keys[0]="void main";
		errs[0]="main函数返回值不能为void,否则会编译出错,请使用int main()，并在最后return 0。\n虽然VC等windows下的编译器支持,C/C++标准中不允许使用void main()!!!";
   		if (language=="3"){
		     	keys[0]="int main";
	        	errs[0]="java要求有public static void main函数";
      		}
		keys[1]="Please";
		errs[1]="除非题目要求，否则不要使用类似‘Please input’这样的提示";		
		keys[2]="请";
		errs[2]="除非题目要求，否则不要使用类似‘请输入’这样的提示";		
		keys[3]="输入";
		errs[3]="除非题目要求，否则不要使用类似‘请输入’这样的提示";		
		keys[3]="input";
		errs[3]="除非题目要求，否则不要使用类似‘Please input’这样的提示";		
		keys[4]="max=%d";
		errs[4]="除非题目要求，否则不要使用类似‘max=’这样的提示";		
		keys[5]="mian";
		errs[5]="是不是把main打成mian了？";	
		for(var i=0;i<keys.length;i++){
			if(src.indexOf(keys[i])!=-1){
				msg+=errs[i]+"\n";
			}
		}
		if(checkIsChinese(src))
			msg+="程序中有中文字符！注意，一般来说本系统中的题目都不会要求输出提示，特别是中文提示。\n请先使用SampleInput做输入，对比输出和SampleOutput，有任何多余的输出（包括提示、多出的逗号、等号空格等等）都会被判错误！\n如有任何程序内容出现中文的括号、分号、引号、空格都会编译出错。";
		if(msg.length>0)
			return confirm(msg+"\n 代码可能有错误，确定要提交么？\n建议先使用题目的SampleInput做测试，看看你的程序输出是否与SampleOutput完全一致。\n多个空格，标点都会被认为是错误答案（WrongAnswer）。\n如果出现编译错误（CompileError），请点击CompileError字样，查看具体编译报错，以便纠正。");					
		else
			return true;
}