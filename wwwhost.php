<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>站主页面</title>
    <!-- <style type="text/css">
        body{
            background-image: url(img1.jpg);
            background-size: cover;
        }
    </style> -->
</head>
<body>
    <a href="loginpage.html" target="_self">注销登录</a><br>
    <h1 align="center">管理员总页面</h1>
    <form method="POST" action="">
    <p align="center"><input type="text" name="search" /><input type="submit" value="搜索" name="find"/></p><!-- search为第一个输入的文本框,find为搜索按钮 -->
    <p align="center"><a href="wwwhost.php"><input type="button" value='显示全部'></a></p>
    <p>
    <p align="center"><a href="loginpage.php"><input type="button" value='返回主页面'></a></p>
    <p align="center"><a href="hostdeletemessage.php"><input type="button" value="查看全部留言"></a></p>
    </form>
    <table align="center" border="1px" cellspacing="0px" width="800px">
    <tr>
        <th>id</th>
        <th>用户名</th>
        <th>密码</th>
        <th>性别</th>
        <th>年级</th>
        <th>班级</th>
        <th>QQ</th>
        <th>管理员操作</th>
    </tr>
    <script language='javascript'>  
        function del(a){
            if(confirm('确定要删除吗？')){
                location.href='delete.php?id=' + a;
        // alert('删除成功！');
            }else{
                return;
            }
        }
        function set(a){
            if(confirm('确定进行管理员更改操作？')){
                location.href='setmanage.php?id=' + a;
        // alert('设置成功');
            }else{
                return;
            }
        }
    </script>
<?php
    $link = mysqli_connect("localhost","root","2356789mmm","liuyanban") or die("错误，未连接到数据库");
    $sql = "select * from admin order by id DESC";
     if(empty($_POST["find"])){
        $result = mysqli_query($link,$sql);
    }else{
        $search = $_POST["search"];
        $sql = "select * from admin where id like '%$search%' or username like '%$search%' or sex like '%$search%' or grade like '%$search%' or cls like '%$search%' or QQ like '%$search%'";
        $result = mysqli_query($link,$sql);
    }
    while($row = mysqli_fetch_array($result))
    {
        $sql1 = "select count(*) from manage where adminusername='$row[1]'";
        $result1 = mysqli_query($link,$sql1);
        $arry1 = mysqli_fetch_array($result1);
        $wheremanage = "设置为管理员";
        if($arry1['count(*)'] != 0){
            $wheremanage = "取消管理员" ;
        }
        echo '<tr align="center">';
        echo "<td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td><td>$row[6]</td>
              <td>
              <a href='hostupdate.php?id=$row[0]'><input type='submit' value='修改' /></a>
              <input type='button' onclick='del($row[0])' value='删除该用户' />
              <input type='button' onclick='set($row[0])' value='$wheremanage' />
              </td>";
        echo '</tr>';

   }  
   mysqli_close($link);
     
?>
    </table>

</body>
</html>