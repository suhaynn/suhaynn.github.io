<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户留言</title>
    <!-- <style type="text/css">
        body{
            background-image: url(img1.jpg);
            background-size: cover;
        }
    </style> -->
</head>
<body>
<body>
    <a href="loginpage.html" target="_self">注销登录</a><br>
    <h1 align="center">用户留言</h1>
    <form method="POST" action="">
    <p align="center"><input type="text" name="search" /><input type="submit" value="搜索" name="find"/></p><!-- search为第一个输入的文本框,find为搜索按钮 -->
    <p align="center"><a href="deletemessage.php"><input type="button" value='显示全部'></a></p>
    <p>
    <p align="center"><a href="admin.php"><input type="button" value='返回管理员页面'></a></p>
    </form>
    <table align="center" border="1px" cellspacing="0px" width="800px">
    <tr>
        <th>id</th>
        <th>用户名</th>
        <th>留言</th>
        <th>管理员操作</th>
    </tr>
    <script language='javascript'>  
        function del(a){
            if(confirm('确定要删除该条留言吗？')){
                location.href='delmessage.php?id=' + a;
        // alert('删除成功！');
            }else{
                return;
            }
        }
    </script>
<?php
    $link = mysqli_connect("localhost","root","2356789mmm","liuyanban") or die("错误，未连接到数据库");
    $sql = "select * from message order by id DESC";
     if(empty($_POST["find"])){
        $result = mysqli_query($link,$sql);
    }else{
        $search = $_POST["search"];
        $sql = "select * from message where id like '%$search%' or username like '%$search%' or messages like '%$search%'";
        $result = mysqli_query($link,$sql);
    }
    while($row = mysqli_fetch_array($result))
    {
        
        if(empty($row)){
            break;
        }
        echo '<tr align="center">';
        echo "<td>$row[0]</td><td>$row[1]</td><td align='left'>$row[2]</td>
              <td>
              <input type='button' onclick='del($row[0])' value='删除该留言' />
              </td>";
        echo '</tr>';
        

   }  
   mysqli_close($link);
     
?>
    </table>

</body>
</html>