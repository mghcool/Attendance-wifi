<?php
session_start();  
//检测是否登录，若没登录则转向登录界面  
if(!isset($_SESSION['login'])){  
    header("Location:login.php".$class);  
    exit();  
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>考勤管理</title>
	<link href="./bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet"/> 
	<link href="./layui-2.4.5/css/layui.css" rel="stylesheet" media="all">
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
					 <a class="navbar-brand">历史记录</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<li class="active"><a href="./">实时状态</a></li>
						<li><a href="record.php">历史记录</a></li>
						<li class="active"><a href="class.php">课程设置</a></li>
						<li><a href="setting.php">系统管理</a></li>
					</ul>
				</div>	
			</nav>

			
		</div>
	</div>
</div>
</body>
</html>