<?php
session_start();  
//检测是否登录，若没登录则转向登录界面  
if(!isset($_SESSION['login'])){  
    header("Location:login.php".$class);  
    exit();  
}
// 从文件中读取数据到PHP变量
$json_string = file_get_contents('class_time.json');
// 把JSON字符串转成PHP数组
$class_time = json_decode($json_string, true);
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
					 <a class="navbar-brand">课程设置</a>
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
			
			<form class="layui-form" action="class.php" method="post">
				<div class="layui-inline">
				    <label class="layui-form-label" style="width:90px;">上课时间</label>
				    <div class="layui-input-inline" style="width:70px;">
				    	<input type="text" name="start_time" id="time1" lay-verify="time"  autocomplete="off" class="layui-input">
				    </div>
				</div>
				<div class="layui-inline">
				    <label class="layui-form-label" style="width:90px;">下课时间</label>
				    <div class="layui-input-inline" style="width:70px;">
				    	<input type="text" name="over_time" id="time2" lay-verify="time"  autocomplete="off" class="layui-input">
				    </div>		    
				</div>
				<div class="layui-inline">
				    <label class="layui-form-label" style="width:90px;">生效日期</label>
				    <div class="layui-input-inline" style="width:95px;">
				    	<input type="text" name="start_date" id="date1" lay-verify="time"  autocomplete="off" class="layui-input">
				    </div>
					-
				    <div class="layui-input-inline" style="width:95px;">
				    	<input type="text" name="over_date" id="date2" lay-verify="time"  autocomplete="off" class="layui-input">
				    </div>
				    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;备注&nbsp;&nbsp;
				    <div class="layui-input-inline" style="width:200px;">
				    	<input type="text" name="remarks"  autocomplete="off" class="layui-input">
				    </div>
				    &nbsp;&nbsp;&nbsp;&nbsp;
				    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo">添加课时</button>
				</div>					
			</form>

			<table class="table table-hover">
				<thead>
					<tr>
						<th class='col-md-1'>课时</th>
						<th class="col-md-1 text-center">课程时间</th>
						<th class="col-md-3 text-center">有效日期</th>
						<th class='col-md-2'>课程备注</th>
						<th class="col-md-1 text-center">操作</th>
					</tr>
				</thead>
				<tbody>
					<?php
					for($i=0;$i<count($class_time);++$i){ 
						echo "<tr class='info'><td>".($i+1)."</td>
						<td class='text-center'>".$class_time[$i]['start_time']."-".$class_time[$i]['over_time']."</td>
						<td class='text-center'>".$class_time[$i]['start_date']."-".$class_time[$i]['over_date']."</td>
						<td >".$class_time[$i]['remarks']."</td>
						<td class='text-center'><a href='class.php?del=".$i."' class='layui-btn layui-btn-xs layui-btn-danger'>删除课时</a></td></tr>"; } 
					?>					
				</tbody>
			</table>
			
<style type="text/css">
td{
　　vertical-align: middle !important;
}
.layui-laydate-content>.layui-laydate-list {padding-bottom: 0px;overflow: hidden;}
.layui-laydate-content>.layui-laydate-list>li{width:50%}
.merge-box .scrollbox .merge-list {padding-bottom: 5px;}
</style>

<script src="./layui-2.4.5/layui.all.js"></script>
<script>
//日期
layui.use(['laydate'], function(){
	var laydate = layui.laydate;
	//生效日期
	laydate.render({
				  elem: '#date1'
				  ,type: 'date'
				  ,format: 'yyyy/MM/dd'
			});
	//失效日期
	laydate.render({
				  elem: '#date2'
				  ,type: 'date'	 
				  ,format: 'yyyy/MM/dd' 
			});
	//上课时间
	laydate.render({
				  elem: '#time1'
				  ,type: 'time'
				  ,format: 'HH:mm'
				  ,ready: formatminutes
			});	
	//下课时间
	laydate.render({
				  elem: '#time2'
				  ,type: 'time'
				  ,format: 'HH:mm'
				  ,ready: formatminutes
			});		
	function  formatminutes(date) {
        var aa = $(".laydate-time-list li ol")[1];
        var showtime = $($(".laydate-time-list li ol")[1]).find("li");
        for (var i = 0; i < showtime.length; i++) {
            var t00 = showtime[i].innerText;
        }
    $($(".laydate-time-list li ol")[2]).find("li").remove();  //清空秒
}
});

function popup(cont,url){	//弹窗
layer.open({
	type: 1			//类型
	,area: '300px'	//定义宽度
	,title: '提示'	//标题
	,offset: 'auto'	//位置
	,content: '<div style="padding: 20px 80px;"><center>'+cont+'</center></div>'	//内容
	,btn: '确定'	//按钮
	,btnAlign: 'c' //按钮居中
	,shade: 0 //不显示遮罩
	,yes: function(index, layero) {window.location.href='./class.php';}	//重定向
});}
</script>
			
		</div>
	</div>
</div>
</body>
</html>

<?php
$start_time=isset($_POST['start_time'])?$_POST['start_time']:null;
$over_time=isset($_POST['over_time'])?$_POST['over_time']:null;
$start_date=isset($_POST['start_date'])?$_POST['start_date']:null;
$over_date=isset($_POST['over_date'])?$_POST['over_date']:null;
$remarks=isset($_POST['remarks'])?$_POST['remarks']:null;

if(isset($_GET['del'])){
	$del=$_GET['del'];
	array_splice($class_time, $del, 1);		//删除值
	//JSON_UNESCAPED_UNICODE防止将中文编码
	$json_string = json_encode($class_time,JSON_UNESCAPED_UNICODE);	
	// 写入文件
	file_put_contents('class_time.json', $json_string);
	echo "<script>popup('删除成功 ！')</script>";
}

if($start_time||$over_time||$start_date||$over_date){
	if($start_time&&$over_time&&$start_date&&$over_date){
		$num = count($class_time);
		$class_time[$num]['start_time']=$start_time;
		$class_time[$num]['over_time']=$over_time;
		$class_time[$num]['start_date']=$start_date;
		$class_time[$num]['over_date']=$over_date;
		if($remarks){$class_time[$num]['remarks']=$remarks;}
			else{$class_time[$num]['remarks']='';}
		$json_string = json_encode($class_time,JSON_UNESCAPED_UNICODE);	//JSON_UNESCAPED_UNICODE防止将中文编码
		// 写入文件
		file_put_contents('class_time.json', $json_string);
		echo "<script>popup('添加成功 ！')</script>";
	}else{
		echo "<script>popup('内容不完整 ！')</script>";
	}
}


?>