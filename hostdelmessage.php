<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>删除用户留言</title>
</head>
<body>
    <?php
   if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
} else {
    // 若没有传入id参数，直接跳转到首页
    header('Location:deletemessage.php');
}
// 执行数据库删除操作
$link = mysqli_connect("localhost","root","2356789mmm","liuyanban") or die("错误，未连接到数据库");
$sql = "delete from message where id = $id";
$result = mysqli_query($link,$sql) or die('信息读取失败');
if($result == true) {
    echo <<<STR
<script type="text/javascript">
    alert("该用户此条留言已删除!")
    window.location.href = 'hostdeletemessage.php';
</script>
STR;
exit;
} else {
    echo <<<STR
<script type="text/javascript">
    alert("该用户此条留言删除失败!")
    windows.location.href = 'hostdeletemessage.php';
</script>
STR;
}
mysqli_close($link);
 ?>
</body>
</html>