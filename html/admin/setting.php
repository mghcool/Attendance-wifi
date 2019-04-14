<?php
session_start();  
//检测是否登录，若没登录则转向登录界面  
if(!isset($_SESSION['login'])){  
    header("Location:login.php".$class);  
    exit();  
}
// 从文件中读取数据到PHP变量
$json_string = file_get_contents('./class.json');
// 把JSON字符串转成PHP数组
$class_all = json_decode($json_string, true);
//获取系统ip、子网掩码、网关、DNS
$ifconfig = shell_exec('ifconfig eth0'); 
$ip_ago = substr(strstr(strstr($ifconfig,' netmask',1), 'inet'), 5);	//位于‘inet’之后5个字符，‘ netmask’之前的字符串
$mask_ago = substr(strstr(strstr($ifconfig,'  broadcast',1), 'netmask'), 8);
$gateway_ago = shell_exec('netstat -r'); 
$gateway_ago = substr(strstr(strstr($gateway_ago,'0.0.0.0',1), 'default'), 16,-5); 
$dns_ago = shell_exec('cat /etc/resolv.conf');
$dns_ago = substr(strstr($dns_ago, 'nameserver'),11,-1);
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
					 <a class="navbar-brand">系统管理</a>
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

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><center>班级管理</center></h3>
				</div>
				<div class="panel-body">
					<div class="col-md-6 column">
						<center>
						<form class="layui-form" action="setting.php" method="post">
							<div class="layui-inline" style="width:170px;">
							<input type="text" name="add_class" required  lay-verify="required" placeholder="填入班级" autocomplete="off" class="layui-input">  
							</div>
							<div class="layui-inline">
								<button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo">添加班级</button>
	 						</div>
						</form>
						</center>
					</div>
					<div class="col-md-6 column">
						<center>
						<form class="layui-form" action="setting.php" method="post">
						<div class="layui-inline" style="width:170px;">
						<select lay-verify="required" required name="del_class">
							<option value="">选择班级</option>
							<?php 
								for($i=0;$i<count($class_all);++$i){ 
									echo '<option value="'.$class_all[$i].'">'.$class_all[$i].'</option>'; 
								} 
							?>
						</select>
						</div>
						<div class="layui-inline">
							<button class="layui-btn layui-btn-danger" lay-submit lay-filter="formDemo">删除班级</button>
 						</div>
					</form>
					</center>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<center><h3 class="panel-title">网络设置</h3></center>
				</div>
				<div class="panel-body">
					
						<form class="layui-form" action="setting.php" method="post">
							<center>
							<div class="layui-inline">
								<label class="layui-form-label" style="width:90px;">IP地址</label>
								<div class="layui-input-inline">
									<input type="text" name="ip" required  placeholder="<?php echo $ip_ago; ?>" lay-verify="required" autocomplete="off" class="layui-input" style="width:140px;">  
								</div>
							</div>
							<div class="layui-inline">
								<label class="layui-form-label" style="width:90px;">子网掩码</label>
								<div class="layui-input-inline">
									<input type="text" name="subnet_mask" placeholder="<?php echo $mask_ago; ?>" autocomplete="off" class="layui-input" style="width:140px;">  
								</div>
							</div>
							<div class="layui-inline">
								<label class="layui-form-label" style="width:90px;">默认网关</label>
								<div class="layui-input-inline">
									<input type="text" name="gateway" placeholder="<?php echo $gateway_ago; ?>" autocomplete="off" class="layui-input" style="width:140px;">  
								</div>
							</div>
							<div class="layui-inline">
								<label class="layui-form-label" style="width:90px;">DNS</label>
								<div class="layui-input-inline">
									<input type="text" name="dns" placeholder="<?php echo $dns_ago; ?>" autocomplete="off" class="layui-input" style="width:140px;">  
								</div>
							</div>
							</center>
							
						<center><br>
								<button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo">保存网络设置</button>
	 						
	 						</center>
						</form>

				</div>
			</div>
	
		</div>
	</div>
</div>

</body>
</html>

<script src="./layui-2.4.5/layui.all.js"></script>
<script>
function popup(cont){	//弹窗
layer.open({
	type: 1			//类型
	,area: '300px'	//定义宽度
	,title: '提示'	//标题
	,offset: 'auto'	//位置
	,content: '<div style="padding: 20px 80px;"><center>'+cont+'</center></div>'	//内容
	,btn: '确定'	//按钮
	,btnAlign: 'c' //按钮居中
	,shade: 0 //不显示遮罩
	,yes: function(index, layero) {window.history.back(-1);}	//重定向
});}
function reboot(){	//重启弹窗 
layer.open({
	type: 2,
	title: '正在重启...',
	area: ['700px', '200px'],
	fixed: true, //不固定
	closeBtn: 0,
	time: 30500,
	content: './reboot.php',
	end: function () {window.history.back(-1);}
});}
</script>

<?php
$add_class=isset($_POST['add_class'])?$_POST['add_class']:null;
$del_class=isset($_POST['del_class'])?$_POST['del_class']:null;
if($add_class){
	$class_all[count($class_all)]=$add_class;	//添加班级
	sort($class_all);	//数组排序
	echo "<script>popup('添加成功 ！')</script>";
}
if($del_class){
	$key = array_search($del_class, $class_all);	//获取班级对应的键值
	array_splice($class_all, $key, 1);		//删除值
	echo "<script>popup('删除成功 ！')</script>";
}
if($add_class || $del_class){
	$json_string = json_encode($class_all,JSON_UNESCAPED_UNICODE);	//JSON_UNESCAPED_UNICODE防止将中文编码
	// 写入文件
	file_put_contents('class.json', $json_string);
}

$ip=isset($_POST['ip'])?$_POST['ip']:null;	//获取ip
if(!(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) && $ip){exit("<script>popup('非法IP地址 ！')</script>");}
if($ip){
	$subnet_mask=isset($_POST['subnet_mask'])?$_POST['subnet_mask']:null;	//获取子网掩码
	$gateway=isset($_POST['gateway'])?$_POST['gateway']:null;	//获取网关
	$dns=isset($_POST['dns'])?$_POST['dns']:null;	//获取dns
	if($subnet_mask == null){$subnet_mask='255.255.255.0';}	//默认子网掩码255.255.255.0
	$subnet_mask = strlen(preg_replace("/0/", "", decbin(ip2long($subnet_mask))));	//转换成子网掩码长度
	$ip =  'static ip_address='.$ip.'/'.$subnet_mask;
	if($gateway != null){$gateway =  'static routers='.$gateway;}
	if($dns != null){$dns = 'static domain_name_servers='.$dns;}
	

	$myfile = fopen("/etc/dhcpcd.conf", "w");
	fwrite($myfile, "hostname\n".
					"clientid\n".
					"persistent\n".
					"option rapid_commit\n".
					"option domain_name_servers, domain_name, domain_search, host_name\n".
					"option classless_static_routes\n".
					"option ntp_servers\n".
					"option interface_mtu\n".
					"require dhcp_server_identifier\n".
					"slaac private\n\n");
	fwrite($myfile, "interface eth0\n".$ip."\n".$gateway."\n".$dns);
	fclose($myfile);
	echo "<script>reboot()</script>";
	system("(sleep 1;sudo reboot;) > /dev/null &", $var);
}
?>