<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理员页面</title>
</head>
<body>
<?php
    $adminusername = $_POST['adminusername'];
    $adminpassword = $_POST['adminpassword'];
    $link = mysqli_connect("localhost","root","2356789mmm","liuyanban") or die("错误，未连接到数据库");
    $sql = "select * from manage where adminusername='$adminusername' and adminpassword='$adminpassword'";
    $result = mysqli_query($link,$sql) or die('信息读取失败');
    $a = mysqli_fetch_array($result);
    if($a['adminusername']==$adminusername && $adminusername!='' && $a['adminpassword']==$adminpassword && $adminpassword!='' && $a['wwwhost'] == 1){
        session_start();
        $_SESSION['adminusername'] = $adminusername;
        $_SESSION['adminpassword'] = $adminpassword;
        header("location: wwwhost.php");
    }
    else if($a['adminusername']==$adminusername && $adminusername!='' && $a['adminpassword']==$adminpassword && $adminpassword!=''){
        session_start();
        $_SESSION['adminusername'] = $adminusername;
        $_SESSION['adminpassword'] = $adminpassword;
        header("location: admin.php");
    }

    else{
        echo <<<STR
        <script type="text/javascript">
            alert('非管理员不能登录！！！');
            window.location.href = 'admin.html';
        </script>
        STR;
        }
    ?>
</body>
</html>