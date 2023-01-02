<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>删除用户信息</title>
</head>

<body>
    <?php
    if(isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        // 若没有传入id参数，直接跳转到首页
        header('Location:admin.php');
    }
    // 执行数据库删除操作
    $link = mysqli_connect("localhost","root","2356789mmm","liuyanban") or die("错误，未连接到数据库");
    // $sql = "delete from admin where id = $id;";
    // $result = mysqli_query($link,$sql) or die('信息读取失败');
    $sql = "select username from admin where id = $id";
    $result = mysqli_query($link,$sql);
    $arry = mysqli_fetch_array($result);
    $username = $arry['username'];
    $sql_0 = "delete from admin where username = '$username'";
    $sql_1 = "delete from message where username = '$username'";
    $sql_2 = "delete from manage where adminusername = '$username';";
    $result_0 = mysqli_query($link,$sql_0) or die(mysqli_error($link));
    $result_1 = mysqli_query($link,$sql_1) or die(mysqli_error($link));
    $result_2 = mysqli_query($link,$sql_2) or die(mysqli_error($link));

    // $address = 'admin.php';
    session_start();
    $adminusername = $_SESSION['adminusername'];
    $sql1 = "select wwwhost from manage where adminusername = '$adminusername'";
    $result1 = mysqli_query($link,$sql1);
    $arry1 = mysqli_fetch_array($result1);
    if($arry1['wwwhost'] == 1)//判断是否为站主,站主为1
    {
        $address = 'wwwhost.php';   
   
    if($result1 == true) {
        echo <<<STR
    <script type="text/javascript">
        alert("用户信息已删除!")
        window.location.href = '$address';
    </script>
    STR;
    exit;
    } else {           
        echo <<<STR
    <script type="text/javascript">
        alert("用户信息删除失败!")
        windows.location.href = '$address';
    </script>
    STR;
    } 
    }
    else{
        $address = 'admin.php';
        if($result1 == true) {
            echo <<<STR
        <script type="text/javascript">
            alert("用户信息已删除!")
            window.location.href = '$address';
        </script>
        STR;
        exit;
        } else {           
            echo <<<STR
        <script type="text/javascript">
            alert("用户信息删除失败!")
            windows.location.href = '$address';
        </script>
        STR;
        } 
    }
    mysqli_close($link);
    ?>
</body>
</html>