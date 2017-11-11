<?php
define('IN_BOOKS', true);
 
require_once 'libs/init.php';
require_once 'libs/libstat.php';
require_once 'libs/lib_cash.php';
require_once 'libs/lib_category.php';

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
    exit(0);
}

else if( $act == 'add' )
{
    $categories = GetCategories(CATEGORY_TYPE_PAY);
    
    $smarty->assign('categories', $categories);
    $smarty->assign('act', 'add');
    $smarty->display('edit_cash.html');
    
    exit(0);
}

else if ( $act = 'insert' )
{
    do_CheckCashStreamInsert();
    
    $date = intval($_REQUEST['date']);
    $type = intval($_REQUEST['type']);
    $cat_id = intval($_REQUEST['cat_id']);
    $sum   = round(floatval($_REQUEST['sum']), 2);
    $pay_method = intval($_REQUEST['pay_method']);
    $intro   = empty($_REQUEST['intro']) ? '' : trim($_REQUEST['intro']);
    
    switch($type)
    {
        case 0:
        case 1:
            break;
        default:
            $type = 0;
    }
    
    switch ( $pay_method )
    {
        case 0:
        case 1:
            break;
        default:
            $pay_method = 0;
    }
    
    if(InsertCashStream($date, $type, $cat_id, $sum, $pay_method, $intro, $_SESSION['gp_id'], $_SESSION['user_id']))
    {
        echo "<script type=text/javascript>alert('添加成功！');window.location.href='category.php';</script>";
    }
    else 
    {
        echo "<script type=text/javascript>alert('添加失败！');window.history.back(-1);</script>";
    }
    
    exit(0);
}

else if($act == "modify")
{
    if( empty($_REQUEST['id']) )
    {
        echo "<script type=text/javascript>alert('非法请求！');window.history.back(-1);</script>";
        exit();
    }
    
    $id = intval($_REQUEST['id']);
    
    if( !CheckCashStreamPrivilege($id, $_SESSION['gp_id']) )
    {
        echo "<script type=text/javascript>alert('非法请求！');window.history.back(-1);</script>";
        exit();
    }
    
    $record = GetCashStreamRecord($id);
    
    $smarty->assign('record', $record);
    $smarty->assign('act', 'modify');
    $smarty->display('edit_cash.html');
    exit();
}

else if($act == "update")
{
    do_CheckCashStreamUpdate();
    
    $id   = intval($_REQUEST['id']);
    $date = intval($_REQUEST['date']);
    $type = intval($_REQUEST['type']);
    $cat_id = intval($_REQUEST['cat_id']);
    $sum   = round(floatval($_REQUEST['sum']), 2);
    $pay_method = intval($_REQUEST['pay_method']);
    $intro   = empty($_REQUEST['intro']) ? '' : trim($_REQUEST['intro']);
    
    switch($type)
    {
        case 0:
        case 1:
            break;
        default:
            $type = 0;
    }
    
    switch ( $pay_method )
    {
        case 0:
        case 1:
            break;
        default:
            $pay_method = 0;
    }
    
    if(UpdateCashStream($id, $date, $type, $cat_id, $sum, $pay_method, $intro, $_SESSION['gp_id']))
    {
        echo "<script type=text/javascript>alert('修改成功！');window.location.href='category.php';</script>";
    }
    else
    {
        echo "<script type=text/javascript>alert('修改失败！');window.history.back(-1);</script>";
    }
    
    exit(0);
}
else if($act == "delete")
{
    
}

function do_CheckCashStreamUpdate()
{
    if( empty($_REQUEST['id']) )
    {
        echo "<script type=text/javascript>alert('非法请求！');window.history.back(-1);</script>";
        exit();
    }
    
    return do_CheckCashStreamInsert();
}

function do_CheckCashStreamInsert()
{
    if( empty($_REQUEST['date']) )
    {
        echo "<script type=text/javascript>alert('日期不能为空！');window.history.back(-1);</script>";
        exit();
    }
    
    if( empty($_REQUEST['type']) )
    {
        echo "<script type=text/javascript>alert('收支类型不能为空！');window.history.back(-1);</script>";
        exit();
    }
    
    if( empty($_REQUEST['category']) )
    {
        echo "<script type=text/javascript>alert('分类不能为空！');window.history.back(-1);</script>";
        exit();
    }
    
    if( empty($_REQUEST['sum']) )
    {
        echo "<script type=text/javascript>alert('金额不能为空！');window.history.back(-1);</script>";
        exit();
    }
    
    if( empty($_REQUEST['pay_method']) )
    {
        echo "<script type=text/javascript>alert('支付方式不能为空！');window.history.back(-1);</script>";
        exit();
    }
    
    if( CheckCatPrivilege(intval($_REQUEST['category']), $_SESSION['gp_id']) )
    {
        echo "<script type=text/javascript>alert('非法分类请求！');window.history.back(-1);</script>";
        exit();
    }
    
    return true;
}



