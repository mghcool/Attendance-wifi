<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>重启</title>
	<link href="./layui-2.4.5/css/layui.css" rel="stylesheet" media="all">
</head>
<body>
<center><br/><p>系统正在重启。请耐心等待！</p><br/><br/></center>

<div class="layui-progress layui-progress-big" lay-showpercent="true" lay-filter="demo">
  <div class="layui-progress-bar layui-bg-red" lay-percent="0%"></div>
</div>

<script src="./layui-2.4.5/layui.all.js"></script>
<script>
var jquery = layui.jquery,element = layui.element;
var num = 0;
var id = setInterval(frame, 300);
function frame() {
	if (num == 100) {
		clearInterval(id);
   	} 
	else {
     		num++; 
     		element.progress('demo', num+'%')
   	}
}

jquery('.site-demo-active').on('click', function()
{
 	var othis = $(this), type = $(this).data('type');
  	active[type] ? active[type].call(this, othis) : '';
});
</script>
