<?php
define('IN_BOOKS', true);

require_once 'libs/init.php';
require_once 'libs/libstat.php';
require_once 'libs/lib_cash.php';

if( empty($_SESSION['user_id']) )
{
    $target = "login.php?refer=";
    $refer = empty($_SERVER['HTTPS']) ? "http://" : "https://". $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']   ;
    $target .= urlencode($refer);
    echo "<script type=text/javascript>window.location.href='$target';</script>";
    exit;
}

$act = empty($_REQUEST['act']) ? 'list' : $_REQUEST['act'];
$now = 1374595200;

reg_common_smarty_vars($smarty);


if( $act == 'list' )
{
    $start_time = GetBeginOfMonth($now);
    $end_time   = GetEndOfMonth($now);
    
    $cash_stream = GetCashStream($start_time, $end_time, $_SESSION['gp_id']);
    
    $smarty->assign('cash_stream', $cash_stream);
    $smarty->assign('title' '查看流水');
}
