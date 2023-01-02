<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>留言板</title>
    <!-- <style type="text/css">
        body{
            background-image: url(img1.jpg);
            background-size: cover;
        }
    </style> -->
    <style type="text/css">
        body{
            background-image: url(img1.jpg);
            background-size: cover;
        }
        textarea{
            background:transparent;
            border-style: 80px;
            /* text-indent: 75px; */
        }
        input::-webkit-input-placeholder {
        color: orange;
        font-size: 12px;
        }
    </style>
</head>
<body>
    <a href="loginpage.html" target="_self">切换账号</a><br>
    <form method="POST" action="message.php" align="center">
    <tr>
        <h1>请您留言</h1>
        <!-- <th style="text-align:right;" name="message"><label for="message">请您留言：</label></th><br> -->
        <td><textarea name="message" rows="15" cols="80" placeholder="请输入您的留言"></textarea></td><br>
    </tr>
    <tr>
        <th></th>
        <td><input type="submit" id="submit" value="提交" name="submit"></td><br>
    </tr>
</form>
<table align="center" border="1px" cellspacing="0px" width="800px">
    <tr>
        <th>用户名</th>
        <th>留言</th>
        <th>操作</th>
    </tr>



<?php
 $link = mysqli_connect("localhost","root","2356789mmm","liuyanban") or die("错误，未连接到数据库");
 $sql = "select id,username,messages from message order by id DESC";
 $result = mysqli_query($link,$sql) or die('信息读取失败');
 $did = $_GET['id'];
 if(!empty($did)){
    $sql="delete from message where id ='$did'";
    $result = mysqli_query($link,$sql);
    if($result!=TRUE){
        echo"error";
        exit(0);
    }
    header("location:messageh.php");
 }
 session_start();
 $usr = $_SESSION['username'];
while($row = mysqli_fetch_array($result)){
    
    $username = $row['username'];
    $con = 0;
    if($username == $usr)
    {
        $con = 1 ;
    }
    $id = $row['id'];
    $messages = $row['messages'];
    if($con ==1 )
    {
        echo '<tr align="center">';
        echo "<td style='color:#FF0000'>$username</td><td align='left'>$messages</td>
              <td>
        <a href='messageh.php?id=$id'><input type='button' align='right' value='删除'></a> 
              </td>";
        echo '</tr>';
    }
    else {
            echo '<tr align="center">';
            echo "<td>$username</td><td align='left'>$messages</td>";
            echo '</tr>';
        }
}
    ?>
</table>
</body>
</html>