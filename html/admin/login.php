<?php 
session_start(); 
$class=isset($_GET['class'])?$_GET['class']:null;//获取原页面班级信息
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>考勤登陆</title>
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

			<div class="container" style="padding-top:70px;">
			    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
			      <div class="panel panel-success">
			        <div class="panel-heading"><h3 class="panel-title">后台管理</h3></div>
			        <div class="panel-body">
			          <form action="./login.php" method="post" class="form-horizontal" role="form">
			            <div class="input-group">
			              <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
			              <input type="text" name="user" value="<?php echo @$_POST['user'];?>" class="form-control" placeholder="用户名" required="required"/>
										<input type='hidden' name='class' value="<?php echo $class ?>"/>
			            </div><br/>
			            <div class="input-group">
			              <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
			              <input type="password" name="pass" class="form-control" placeholder="密码" required="required"/>
			            </div><br/>
			            <div class="form-group">
			              <div class="col-xs-12"><input type="submit" value="登陆" class="btn btn-info form-control"/></div>
			            </div>
			          </form>
			        </div>
			      </div>
			    </div>
			</div>
<?php
$user=isset($_POST['user'])?$_POST['user']:null;
$pass=isset($_POST['pass'])?$_POST['pass']:null;
$class=isset($_POST['class'])?$_POST['class']:null;
if($class){
	$class = "?class=".$class;
}
if($user && $pass){
	$servername = "127.0.0.1";
	$username = "root";
	$password = "mgh";
	$dbname = "Attendance";
	// 创建连接
	$conn = mysqli_connect($servername, $username, $password, $dbname); 
	// 检测连接
	if (!$conn) {
			echo "<script>window.alert('无法连接数据库 ！');</script>";
	}else{
		$result = mysqli_query($conn,"SELECT * FROM Administrator WHERE `user` LIKE '$user' AND `pass` LIKE '$pass'");	//查询要注册的姓名
		$num=$result->num_rows;	//查到的行数
		if($num == 0){
			echo "<script>window.alert('密码错误 ！');</script>";
		}else{
			$_SESSION['login']=1;
			header("Location:./".$class);
			exit();
		}
	}
}
?>
		</div>
	</div>
</div>

</body>
</html>
