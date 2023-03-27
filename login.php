<?php
//添加一个登录页面；
//raptorzhang 2022 1124
include_once("config.php");
include_once("config_patch.php");
include_once("functions.php");
$msg = '';
//退出操作；
if(isset($_GET['action']) && $_GET['action'] =='logout'){
    $msg ='再见！下次再来哟！';$_POST = [];  $_SESSION = [];
}

//已经登录的时候；
if(isset($_SESSION['loginBl']) && $_SESSION['loginBl']===true){
    header('Location:windows.php');
}

//登录验证；
if(isset($_POST['userName']) && isset($_POST['passWord']) ){

    $u = trim($_POST['userName']);$p = trim($_POST['passWord']);
    //输入值合法的时候；
    if(strlen($u)>3 && strlen($p)>3){
        if($u==$user && $p == $passwd){
            $_SESSION['loginBl'] = true;
            header('Location:windows.php');
        }else{
            $msg ='不对头';
        }
    }else{
        $msg = '非法输入！';
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body style="text-align: center">
        <?php if($msg!=''){?>
        <div style="margin:20px auto; width: 500px; background: #9d1e15; color: #FFFFFF; padding: 20px; text-align: center"><?php echo $msg?></div>
       <?php }?>
        <fieldset style="display: block; margin:100px auto;width: 300px;border: 1px #81A1C1 solid">
            <form action="login.php?action=login" method="post" >
            <LEGEND style=";border: 1px #81A1C1 solid; background: #0a0e14; color: #ffffff">登录</LEGEND>
                <br />用户：<input style="width: 200px" type="text" name="userName"> <br /> <br />
            密码：<input type="password" style="width: 200px" name="passWord"> <br /> <br />
            <input type="submit" name="登录" > <br /> <br />
            </form>
        </fieldset>
    </form>
</body>
</html>