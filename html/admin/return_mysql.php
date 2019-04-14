<?php
$status=isset($_GET['status'])?$_GET['status']:null;
$class=isset($_GET['class'])?$_GET['class']:null;
$sql_class = '';
if($class!='false'){
    $sql_class="`class` LIKE '$class' AND";
}

if($status=='on'){
    $color = "class='success'";
}else{
    $color = "class='danger'";
}

$servername = "127.0.0.1";
$username = "root";
$password = "mgh";
$dbname = "Attendance";
// 创建连接
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    echo "<script>popup('无法连接数据库！','./index.php')</script>";
}else{
    mysqli_query($conn , "set names utf8");
    $sql = "SELECT * FROM `user` WHERE $sql_class `status` LIKE '$status'";
    $result = mysqli_query($conn, $sql);
 
    if (mysqli_num_rows($result) > 0) {
        
        
        $num = 0;
        while($row = mysqli_fetch_assoc($result)) {
            $num++;
            echo "<tr ".$color."><td>".$num."</td><td>".$row["name"]."</td><td>".$row["class"]."</td><td>".substr($row["date"],5,11)."</td></tr>";
        }
    } else {
        if($status=='on'){
            echo "<br>全部未到 ！";
        }else{
            echo "<br>全部到齐 ！";
        }
    }
}
?>
