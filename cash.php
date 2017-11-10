<?php
define('IN_BOOKS', true);

require_once 'libs/init.php';
require_once 'libs/libstat.php';
require_once 'libs/lib_cash.php';

if( empty($_SESSION['user_id']) )
{
    $target = "login.php?refer=";
    $refer = empty($_SERVER['HTTPS']) ? "http://" : "https://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']   ;
    $target .= urlencode($refer);
    echo "<script type=text/javascript>window.location.href='$target';</script>";
    exit;
}

$act = empty($_REQUEST['act']) ? 'list' : $_REQUEST['act'];
$now = 1374595200;

reg_common_smarty_vars($smarty);


if( $act == 'list' )
{
    $start_time = empty($_REQUEST['start_time']) ? GetBeginOfMonth($now) : intval($_REQUEST['start_time']);
    $end_time   = empty($_REQUEST['end_time']) ? GetEndOfMonth($now) : intval(($_REQUEST['end_time'] ));
    $count_per_page = empty($_REQUEST['count_per_page']) ? 20 : intval(($_REQUEST['count_per_page'] ));
    $current_pos   = empty($_REQUEST['current_pos']) ? 0 : intval(($_REQUEST['current_pos'] ));
    
    $cash_stream = GetCashStream($start_time, $end_time, $_SESSION['gp_id'], $current_pos, $count_per_page);
    
    $smarty->assign('start_time', $start_time);
    $smarty->assign('end_time', $end_time);
    $smarty->assign('cash_stream', $cash_stream['stream']);
    $smarty->assign('cash_stream', $cash_stream['stream']);
    $smarty->assign('count', $cash_stream['count']);
    $smarty->assign('count_per_page', $count_per_page);
    $smarty->assign('current_pos', $current_pos);
    $smarty->assign('title', '查看流水');   
    $smarty->display('cash.html');
    
}
