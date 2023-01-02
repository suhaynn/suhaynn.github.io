<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>注册页面</title>
    </head>
    <body>
    <?php
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sex = $_POST['sex'];
    $grade = $_POST['grade'];
    $cls = $_POST['cls'];
    $QQ = $_POST['QQ'];
    $link = mysqli_connect("localhost","root","2356789mmm","liuyanban") or die("错误，未连接到数据库");
    $sql = "select * from admin where username='$username'";
    $result = mysqli_query($link,$sql);
if($result->num_rows>0)//如果数据库内存在相同用户名，则'$result'接收到的变量为'true'所以大于1为真，则返回'用户名已存在'
{
    echo "用户名已存在，请重新注册！";
    echo "<a href=loginpage.html>[返回]</a>";
} 
else //否则可以成功注册递交
{
    $sql = "insert into admin(username,password,sex,grade,cls,QQ) values('{$username}','${password}','${sex}','${grade}','${cls}','${QQ}')";
    $result = mysqli_query($link,$sql) or die('信息读取失败');
    if (!$result) 
    {
        printf("Error: %s\n", mysqli_error($link));
        exit();
    }
$sql="select username,password from admin where username='$username'";
$result = mysqli_query($link,$sql) or die('信息读取失败');
$a = mysqli_fetch_array($result);
if($a['username']==$username && $username!='')
{
    echo <<<STR
    <script type="text/javascript">
        alert('注册成功！');
        window.location.href = 'loginpage.html';
    </script>
    STR;
//     echo "注册成功,三秒后自动跳转至登录页面<br>";
//     header ("Refresh:3;url=loginpage.html");
//     echo'<a href="loginpage.html" target="_self">点击此处立刻跳转</a>';
} 
 else
 {
    echo "注册失败，三秒后请重新注册账号！";
    header ("Refresh:3;url=loginpage.html");
    echo'<a href="loginpage.html" target="_self">点击此处立刻跳转</a>';
 }
}
?>
    </body>
</html>