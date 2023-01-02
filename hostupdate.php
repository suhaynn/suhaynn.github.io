<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改用户信息</title>
</head>
<script>  
二次确认密码是否输入正确
    function checkpassword()
    {  
            if(password.value != repassword.value)
            {  
                alert("两次输入密码不一致！请重新输入密码！")
                password.value = "";  
                repassword.value = "";  
            }
    }
//     不能为空
function checkempty() {
    $user_username = document.getElementById("username").value;
    $user_password = document.getElementById("repassword").value;
    // $user_sex = document.getElementById("sex").value;
    $user_grade = document.getElementById("grade").value;
    $user_cls = document.getElementById("cls").value;
    $user_QQ = document.getElementById("QQ").value;
    if ($user_username == '' || $user_username.length < 2 || $user_username.length > 12) {
        alert("用户名不能为空且用户名长度是2---12位哦");
        return;
       
    }
    if ($user_password == '') {
        alert("密码不能为空");
        return;
     
    }

    // if ($user_sex == '') {
    //     alert("用户名不能为空");
    //     window.history.go(-1);
    // }
    if ($user_grade == '') {
        alert("年级不能为空");
        return;
       
    }
    if ($user_cls == '') {
        alert("班级不能为空");
        return;
      
    }
    if ($user_QQ == '') {
        alert("QQ不能为空");
        return;
       
    }
    document.getElementById('sub').value='1';
    var form=document.getElementById('form');
    form.submit();
}
</script>
<?php
//建立数据库连接
$id = $_GET['id'];
$link = mysqli_connect("localhost","root","2356789mmm","liuyanban") or die("错误，未连接到数据库");
$sql = "select * from admin where id = $id";
$result = mysqli_query($link,$sql) or die('信息读取失败');
$admin = mysqli_fetch_assoc($result);
mysqli_free_result($result);
//更新留言信息
if(($_POST['sub'])==1) { //  用户已经提交表单
	//获取表单数据
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
        echo <<<STR
        <script type="text/javascript">
            alert("用户名已存在，请重新修改!")
            window.location.href = 'wwwhost.php';
        </script>
        STR;
        exit;
        // echo "用户名已存在，请重新注册！";
        // header("location: admin.php");
        // //echo "<a href=loginpage.html>[返回]</a>";
    } 
    else{
	//执行sql语句
	$sql = "update admin set username = '$username',password = '$password',sex = '$sex',grade = '$grade',cls = '$cls',QQ='$QQ' where id = '$id'";
	if(mysqli_query($link,$sql) && mysqli_affected_rows($link) == 1) {
		echo <<<STR
<script type="text/javascript">
	alert('修改成功！');
	window.location.href = 'wwwhost.php';
</script>
STR;
	} else {
		echo mysqli_error($link);
	}
} }
?>
<body>
	<form action="" method="post" id="form">
        
    用户名：<input type="text" name="username" id="username" placeholder="请输入新的用户名"><br>
	密   码：<input type="password" name="password" id="password" placeholder="请输入新的密码"><br>
    确认密码：<input type="password" id="repassword" onblur="checkpassword()" placeholder="请再次输入新的密码"><br>
    性别:<label for="nan">男</label><input type="radio" name="sex" value="男" id="nan">
         <label for="nv">女</label><input type="radio" name="sex" value="女" id="nv">
         <label for="renyao">人妖</label><input type="radio" name="sex" value="人妖" id="renyao"><br>
         
         <tr>
            <th style="text-align:right;"><label for="grade">年级：</label></th>
            <td>
            <select name="grade" id="grade">
            <option value="">请选择你的新年级</option>
            <option value="2022级">2022级</option>
            <option value="2021级">2021级</option>
            <option value="2020级">2020级</option>
            <option value="2019级">2019级</option>
            <option value="2018级">2018级</option>
            <option value="2017级">2017级</option>
            <option value="2016级">2016级</option>
            <option value="2015级">2015级</option>
            </select>
            </td>
        </tr><br>
       	 
        <tr>
            <th style="text-align:right;"><label for="cls">班级：</label></th>
            <td>
            <select name="cls" id="cls">
            <option value="">请选择你的新班级</option>
            <option value="22计科">22计科</option>
            <option value="22软工安全1班">22软工安全1班</option>
            <option value="22软工安全2班">22软工安全2班</option>
            <option value="22软工数据库1班">22软工数据库1班</option>
            <option value="22软工数据库2班">22软工数据库2班</option>
            <option value="22软工移动1班">22软工移动1班</option>
            <option value="22软工移动2班">22软工移动2班</option>
            <option value="22软工移动3班">22软工移动3班</option>
            <option value="22软工云计算1班">22软工云计算1班</option>
            <option value="22软工云计算2班">22软工云计算2班</option>
            <option value="22软工云计算3班">22软工云计算3班</option>
            <option value="22软工云计算4班">22软工云计算4班</option>
            <option value="22软工云计算5班">22软工云计算5班</option>
            <option value="22软工智能1班">22软工智能1班</option>
            <option value="22软工智能3班">22软工智能2班</option>
            <option value="22软工智能2班">22软工智能3班</option>
            <option value="22软工智能4班">22软工智能4班</option>
            <option value="22软工智能5班">22软工智能5班</option>
            <option value="22软工智能6班">22软工智能6班</option>
            <option value="22软工智能7班">22软工智能7班</option>
            <option value="22大数据">22大数据</option>
            <option value="22人工智能">22人工智能</option>
            <option value="22网络空间安全">22网络空间安全</option>
            </select>
            </td>
        </tr><br>
         
        QQ:<input type="text" name="QQ" id="QQ" placeholder="请输入新的QQ"><br>
        <input type="hidden" name="sub" value="0" id="sub">
		<p><input type="button" value="更新" onclick="checkempty()" />
        <a href="wwwhost.php" target="_self">返回</a></p> 
	</form>
</body>
</html>