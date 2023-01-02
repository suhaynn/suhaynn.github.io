<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>留言板</title>
</head>
<body>
    <?php
    session_start();
    $username = $_SESSION['username'];
    $message = $_POST['message'];
    $link = mysqli_connect("localhost","root","2356789mmm","liuyanban") or die("错误，未连接到数据库");
    $sql = "insert into message(username,messages) values('$username','$message')";
    $result = mysqli_query($link,$sql) or die('信息写入失败');
        if($result==TRUE){
        echo <<<STR
        <script type="text/javascript">
            
            window.location.href = 'messageh.php';
        </script>
        STR; 
    }
    else
    echo <<<STR
    <script type="text/javascript">
        alert('留言失败！');
        window.location.href = 'messageh.php';
    </script>
    STR;
    ?>
</body>
</html>