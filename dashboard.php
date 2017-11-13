<?php

define('IN_BOOKS', true);

require_once 'libs/init.php';
require_once 'libs/libstat.php';

if( empty($_SESSION['user_id']) )
{
    $target = "login.php?refer=";
    $refer = empty($_SERVER['HTTPS']) ? "http://" : "https://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']   ;
    $target .= urlencode($refer);
    echo "<script type=text/javascript>window.location.href='$target';</script>";
    exit;
}
$act = empty($_REQUEST['act']) ? 'overview' : $_REQUEST['act'];
$now = time();

reg_common_smarty_vars($smarty);
$smarty->assign('nav_choice', 'nav_dashboard');
if ($act === 'overview')
{
    $FirstDay = GetBeginOfMonth($now);
    $LastDay  = GetEndOfMonth($now);

    //TODO: Group
    $sql = "select * from zb.stream where date >= $FirstDay and date < $LastDay";
    $pay = 0.0;
    $earn = 0.0;
    
    if ( $result = $db->query($sql) )
    {
        while( $row = $result->fetch_assoc() )
        {
            //$row['date'] = date('Y-m-d H:i:s', $row['date']);
            if($row['dire_type'] == 0)
            {
                $pay += $row['sum'];
            }
            else
            {
                $earn += $row['sum'];
            }
        }
    }
    
    $smarty->assign('act', 'overview');
    $smarty->assign('pay', $pay);
    $smarty->assign('earn', $earn);
    $smarty->assign('timestr', date('Y-m', $FirstDay));
    $smarty->assign('title', '统计信息');
    $smarty->assign('lastip', $_SESSION['last_ip']);
    $smarty->assign('username', $_SESSION['username']);
    $smarty->display('dashboard.html');
    
}