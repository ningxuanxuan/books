<?php

define('IN_BOOKS', true);

require_once 'libs/init.php';
require_once 'libs/libstat.php';

$act = empty($_REQUEST['act']) ? 'overview' : $_REQUEST['act'];
$now = time();
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
    $smarty->display('dashboard.html');
    
}