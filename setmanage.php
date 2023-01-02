<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>设置管理员</title>
</head>

<body>
    <?php
    if(isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        // 若没有传入id参数，直接跳转到首页
        header('Location:wwwhost.php');
    }
    // 执行数据库删除操作
    $link = mysqli_connect("localhost","root","2356789mmm","liuyanban") or die("错误，未连接到数据库");
    $sql = "select * from admin where id = $id";
    $result = mysqli_query($link,$sql) or die('信息读取失败');
    $arr = mysqli_fetch_array($result);
 
    $sql1 = "select count(*) from manage where adminusername='$arr[1]'";
    $result1 = mysqli_query($link,$sql1) or die('信息读取失败');
    $arr1 = mysqli_fetch_array($result1);
    if($arr1['count(*)'] == 0){
        $sql1 = "insert into manage(adminusername,adminpassword) values('$arr[username]','$arr[password]')";
        $result1 = mysqli_query($link,$sql1) or die('信息读取失败');
    }else if($arr1['count(*)'] == 1){
        $sql1 = "delete from manage where adminusername = '$arr[username]'";
        $result1 = mysqli_query($link,$sql1) or die('信息读取失败');
    }
   
    if($result1 == true) {
        echo <<<STR
    <script type="text/javascript">
        alert("管理员修改成功!")
        window.location.href = 'wwwhost.php';
    </script>
    STR;
    exit;
    }else{           
        echo <<<STR
    <script type="text/javascript">
        alert("管理员修改失败!")
        windows.location.href = 'wwwhost.php';
    </script>
    STR;
    }
    mysqli_close($link);
    ?>
</body>
</html>