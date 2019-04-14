<?php
session_start();  
//检测是否登录，若没登录则转向登录界面  
if(!isset($_SESSION['login'])){  
    header("Location:login.php".$class);  
    exit();  
} 
// 从文件中读取数据到PHP变量
$json_string = file_get_contents('class.json');
// 把JSON字符串转成PHP数组
$class_all = json_decode($json_string, true);

$class=isset($_GET['class'])?$_GET['class']:null;
if($class){
	$class = "?class=".$class;
} 
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>考勤管理</title>
	<link href="./bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet"/> 
	<script type="text/javascript" src="./jquery-2.1.1/jquery.min.js"></script>
	<script src="./bootstrap-3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="page-header">
				<h1>无线 <small>考勤</small></h1>
			</div>
			<nav class="navbar navbar-default" role="navigation">
				<div class="navbar-header">
					 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> 
					 <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span>
					 <span class="icon-bar"></span>
					 </button> 
					 <a class="navbar-brand">实时状态</a>
				</div>
				
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<li class="active"><a href="./">实时状态</a></li>
						<li><a href="record.php">历史记录</a></li>
						<li class="active"><a href="class.php">课程设置</a></li>
						<li><a href="setting.php">系统设置</a></li>
					</ul>
				</div>	
			</nav>

			<div class="dropdown">
				 <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    	选择班级 <span class="caret"></span></button>
		  		<ul class="dropdown-menu">	
					<?php 
						for($i=0;$i<count($class_all);++$i){ 
							echo '<li><a href="?class='.$class_all[$i].'">'.$class_all[$i].'</a></li>'; 
						} 
					?>
		  		</ul>
			</div>

			<div class="row clearfix">
			<br>
				<div class="col-md-6 column">
				<span class="label label-success">上课已到</span>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>序号</th>
								<th>姓名</th>
								<th>班级</th>
								<th>时间</th>
							</tr>
						</thead>
						<tbody id="useron">					
						</tbody>
					</table>
				</div>
				<div class="col-md-6 column">
				<span class="label label-danger">上课未到</span>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>序号</th>
								<th>姓名</th>
								<th>班级</th>
								<th>时间</th>
							</tr>
						</thead>
						<tbody id="useroff">
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
//获取url参数
function getQueryVariable(variable)
{
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
}

//初始显示
show();
setInterval(function(){ show(); }, 500);//总时间
//两个时间不能相同
function show(){
	user_on();
	setTimeout(function(){ user_off(); }, 250);//总时间一半
}

function user_on()
{
    if (window.XMLHttpRequest){
        // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
        xmlhttp=new XMLHttpRequest();
    }else{
        // IE6, IE5 浏览器执行代码
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            document.getElementById("useron").innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET","return_mysql.php?status=on&class="+getQueryVariable("class")+"&random="+Math.random(),true);
    xmlhttp.send();
}

function user_off()
{
    if (window.XMLHttpRequest){
        // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
        xmlhttp=new XMLHttpRequest();
    }else{
        // IE6, IE5 浏览器执行代码
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            document.getElementById("useroff").innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET","return_mysql.php?status=off&class="+getQueryVariable("class")+"&random="+Math.random(),true);
    xmlhttp.send();
}
</script>

</body>
</html>