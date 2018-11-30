<?php
define('IN_BOOKS', true);

require_once 'libs/init.php';
require_once 'libs/lib_plan.php';

$act = empty($_REQUEST['act']) ? 'list' : $_REQUEST['act'];

if( empty($_SESSION['user_id']) )
{
    $target = "login.php?refer=";
    $refer = empty($_SERVER['HTTPS']) ? "http://" : "https://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']   ;
    $target .= urlencode($refer);
    echo "<script type=text/javascript>window.location.href='$target';</script>";
    exit;
}

$act = empty($_REQUEST['act']) ? 'list' : $_REQUEST['act'];
$now = time();

reg_common_smarty_vars($smarty);
$smarty->assign('nav_choice', 'nav_plan');

if($act == 'list')
{
    $list = GetGroupPlans($_SESSION['gp_id']);
    $smarty->assign('plans', $list);
    $smarty->assign('title', '查看计划'); 
    $smarty->display('plan.html');
}