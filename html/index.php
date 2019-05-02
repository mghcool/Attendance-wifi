<?php
// 从文件中读取数据到PHP变量
$json_string = file_get_contents('./admin/class.json');
// 把JSON字符串转成PHP数组
$class_all = json_decode($json_string, true);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>无线签到</title>
<link href="./admin/layui-2.4.5/css/layui.css" rel="stylesheet" media="all">  
</head>
<body>	
<center>
	<br><br><br>
	<svg t="1552837244180" class="icon" style="" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2743" xmlns:xlink="http://www.w3.org/1999/xlink" width="200" height="200"><defs><style type="text/css"></style></defs><path d="M1005.704775 388.466526a18.593429 18.593429 0 0 1-13.18511-5.464268C864.473041 254.936986 693.943137 184.423691 512.354152 184.423691S160.235262 254.936986 32.188638 383.002258a18.630728 18.630728 0 1 1-26.370219-26.370219C140.914507 221.535951 320.806399 147.143586 512.354152 147.143586s371.458294 74.392365 506.535732 209.488453a18.630728 18.630728 0 0 1-13.185109 31.834487z" fill="#424A60" p-id="2744"></path><path d="M128.624568 498.087565a18.630728 18.630728 0 0 1-13.18511-31.834487c218.86909-218.86909 574.978947-218.831791 793.810738 0a18.630728 18.630728 0 1 1-26.370219 26.370219c-204.322576-204.322576-536.766373-204.322576-741.0703 0a18.593429 18.593429 0 0 1-13.185109 5.464268z" fill="#9777A8" p-id="2745"></path><path d="M786.444047 607.708603a18.593429 18.593429 0 0 1-13.185109-5.464267c-69.562177-69.562177-162.230932-107.886647-260.904786-107.886647s-191.32396 38.32447-260.904787 107.886647a18.630728 18.630728 0 1 1-26.370219-26.370219c76.611641-76.611641 178.642384-118.815182 287.275006-118.815182 108.632622 0 210.644715 42.20354 287.275005 118.815182a18.630728 18.630728 0 0 1-13.18511 31.834486zM512.354152 632.512275c-67.36155 0-122.17207 54.810519-122.17207 122.190719s54.810519 122.190719 122.17207 122.190719c67.3802 0 122.190719-54.810519 122.190719-122.190719s-54.810519-122.190719-122.190719-122.190719z" fill="#7FABDA" p-id="2746"></path></svg>
	<h5><font size="8">无线考勤</font></h5>
	<br/><br/><br/><br/>
</center>
<div style="width:70%" class="layui-container">  
	<form class="layui-form" action="#">
		<div class="layui-form-item">
		<input type="text" name="name" required  lay-verify="required" placeholder="请输入姓名" autocomplete="off" class="layui-input">  
		</div>
 
		<div class="layui-form-item">
		  <select name="class" lay-verify="required" >
			<option value="">请选择班级</option>
			<?php 
				for($i=0;$i<count($class_all);++$i){ 
					echo '<option value="'.$class_all[$i].'">'.$class_all[$i].'</option>'; 
				} 
			?>
		  </select>
		</div>

  		<div class="layui-form-item">
  			<br/><br/>
			<button class="layui-btn layui-btn-fluid" lay-submit lay-filter="formDemo" onclick="layer.load(0,{time: 4*1000, shade: [0.7, '#393D49']}, {shadeClose: true});">立即提交</button>
 		</div>	
	</form>
</div>
	
<script src="./admin/layui-2.4.5/layui.all.js"></script>
<script>
//弹窗
function popup(cont){
layer.open({
	type: 1			//类型
	,area: '300px'	//定义宽度
	,title: '提示'	//标题
	,offset: 'auto'	//位置
	,content: '<div style="padding: 20px 80px;"><center>'+cont+'</center></div>'	//内容
	,btn: '确定'	//按钮
	,btnAlign: 'c' //按钮居中
	,shade: 0 //不显示遮罩
	,yes: function(index, layero) {window.location.href='./';}	//重定向
});}
</script>

<?php
$name=isset($_GET['name'])?$_GET['name']:null;
$class=isset($_GET['class'])?$_GET['class']:null;
$date = date('Y-m-d H:i:s', time());
if($name && $class){
	$ip = $_SERVER['REMOTE_ADDR'];	//获取IP
	$cmd = 'arp -a '.$ip;	
	exec($cmd,$results,$ret);	//执行arp命令
	$at = strpos($results[0],"at");	//获取关键字at的位置
	$mac = substr($results[0],$at+3,18);	//根据at的位置截取mac
	
	//将用户信息写入数据库
	$servername = "127.0.0.1";
	$username = "root";
	$password = "mgh";
	$dbname = "Attendance";
	// 创建连接
	$conn = mysqli_connect($servername, $username, $password, $dbname); 
	// 检测连接
	if (!$conn) {
	    echo "<script>popup('无法连接数据库！')</script>";
	}else{
		$result = mysqli_query($conn,"SELECT * FROM user WHERE name='".$name."'");	//查询要注册的姓名
		$num=$result->num_rows;	//查到的行数
		if($num == 0){
			$sql = "INSERT INTO user (name, class, mac, status, date)VALUES ('".$name."', '".$class."', '".$mac."', 'on', '".$date."')";
			//写入
			if (mysqli_query($conn, $sql)) {
				echo "<script>popup('".$name." 注册成功')</script>";
			} else {
				echo "<script>popup('此设备已经注册过 ！')</script>";
			}
			//关闭连接
			mysqli_close($conn);
		}else{
			echo "<script>popup('此姓名已经注册过 ！')</script>";
		}
	}
}
?>
	
</body>
</html>