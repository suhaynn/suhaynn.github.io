<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录页面</title>
</head>
<body>
    <?php
    $username = $_POST['username'];
    $password = $_POST['password'];
    $link = mysqli_connect("localhost","root","2356789mmm","liuyanban") or die("错误，未连接到数据库");
    $sql = "select* from admin where username='$username' and password='$password'";
    $result = mysqli_query($link,$sql) or die('信息读取失败');
    $a = mysqli_fetch_array($result);
    if($a['username']==$username && $username!='' && $a['password']==$password && $password!=''){
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header("location: messageh.php");
    }
    else{
        echo <<<STR
        <script type="text/javascript">
            alert("用户名或密码错误，请重新输入！")
            window.location.href = 'loginpage.html';
        </script>
        STR;
        exit;
        //header('location:loginpage.html');
    // else{echo "用户名或密码错误，请重新登录或注册账号！<br>";
    //      echo'<a href="loginpage.html" target="_self">重新登录</a><br>';
    //      echo'<a href="register.html" target="_self">注册</a><br>';
        
     }
    ?>
</body>
</html>