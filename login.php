<?php
define('IN_BOOKS', true);

require_once 'libs/init.php';

$act=empty($_REQUEST['act'])?'show_login':$_REQUEST['act'];
$refer=empty($_REQUEST['refer']) ? '' : filter_htmlspecialchars($_REQUEST['refer']);

if('show_login'==$act)
{
    $smarty->assign('refer', $refer);
    $smarty->display('login.html');
}

if('check_login'==$act)
{
    $username=$_REQUEST['username'];
    $password=md5($_REQUEST['password']);
    
    if(empty($username))
    {
        echo "<script type=text/javascript>alert('用户名不能为空!');window.history.back(-1);</script>";
        exit();
    }
    
    
    if(empty($password))
    {
        echo "<script type=text/javascript>alert('密码不能为空!');window.history.back(-1);</script>";
        exit();
    }
    
    
    $sql='select * from user as a left join `group` as b ON a.gp_id=b.gp_id where username=\''.$username.'\' and password=\''.$password.'\'';
    
    $res = array();
    if ( $result = $db->query($sql) )
    {
        $res = $result->fetch_assoc();
    }
        
    
    if(empty($res))
    {
        echo "<script type=text/javascript>alert('登录失败!');window.history.back(-1);</script>";
        exit();
    }
    
    $_SESSION['username']	=	$res['username'];
    $_SESSION['user_id']	=	$res['user_id'];
    $_SESSION['last_ip']	=	$res['last_ip'];
    $_SESSION['last_date']  =	date("Y-m-d H:i:s",$res['last_date']);
    $_SESSION['real_name']  =	$res['real_name'];
    $_SESSION['gp_id']      =   $res['gp_id'];
    
    $ip=real_ip();
    $date=time();
    $sql='update user set last_ip=\''.$ip.'\',last_date=\''.$date.'\' where user_id=\''.$_SESSION['user_id'].'\'';
    
    $db->query($sql);
    $target = empty($refer) ? 'dashboard.php' : urldecode($refer);
    echo "<script type=text/javascript>window.location.href='$target';</script>";
    exit;
}

if('loginout'==$act)
{
    $_SESSION=array();
    echo "<script type=text/javascript>window.location.href='login.php';</script>";
    exit;
}