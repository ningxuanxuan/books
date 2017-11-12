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
$now = time();

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
    $arrCate = GetCategories(CATEGORY_TYPE_PAY);
    
    $categories = array();
    
    foreach ($arrCate as $cat)
    {
        if($cat['parent'] == 0) //根分类
        {
            $categories[$cat['cat_id']][] = array('cat_id' => $cat['cat_id'],
                'cat_name' => $cat['name'], 'parent' => $cat['parent']);
        }
        else
        {
            $categories[$cat['parent']][] = array('cat_id' => $cat['cat_id'],
                'cat_name' => $cat['name'], 'parent' => $cat['parent']);
        }
        
    }
    
    $smarty->assign('title', '记账');   
    $smarty->assign('categories', $categories);
    $smarty->assign('act', 'add');
    $smarty->display('edit_cash.html');
    
    exit(0);
}

else if ( $act == 'insert' )
{
    do_CheckCashStreamInsert();
    

    $date = empty($_REQUEST['date']) ? 0 : intval($_REQUEST['date']);
    $type = empty($_REQUEST['type']) ? 0 : intval($_REQUEST['type']);
    $cat_id = empty($_REQUEST['category']) ? 0 : intval($_REQUEST['category']);
    $sum   = empty($_REQUEST['sum']) ? 0.0 : round(floatval($_REQUEST['sum']), 2);
    $pay_method = empty($_REQUEST['pay_method']) ? 0 : intval($_REQUEST['pay_method']);
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
        echo "<script type=text/javascript>alert('添加成功！');window.location.href='cash.php?act=add';</script>";
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
    
    if( count($record) == 0 )
    {
        echo "<script type=text/javascript>alert('非法请求！');window.history.back(-1);</script>";
        exit();
    }
    
    $arrCate = GetCategories($record['type']);
    
    $categories = array();
    
    foreach ($arrCate as $cat)
    {
        if($cat['parent'] == 0) //根分类
        {
            $categories[$cat['cat_id']][] = array('cat_id' => $cat['cat_id'],
                'cat_name' => $cat['name'], 'parent' => $cat['parent']);
        }
        else
        {
            $categories[$cat['parent']][] = array('cat_id' => $cat['cat_id'],
                'cat_name' => $cat['name'], 'parent' => $cat['parent']);
        }
        
    }
    
    $smarty->assign('title', '修改记录');   
    $smarty->assign('categories', $categories);
    $smarty->assign('record', $record);
    $smarty->assign('act', 'modify');
    $smarty->display('edit_cash.html');
    exit();
}

else if($act == "update")
{
    do_CheckCashStreamUpdate();
    
    $id   = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
    $date = empty($_REQUEST['date']) ? 0 : intval($_REQUEST['date']);
    $type = empty($_REQUEST['type']) ? 0 : intval($_REQUEST['type']);
    $cat_id = empty($_REQUEST['category']) ? 0 : intval($_REQUEST['category']);
    $sum   = empty($_REQUEST['sum']) ? 0.0 : round(floatval($_REQUEST['sum']), 2);
    $pay_method = empty($_REQUEST['pay_method']) ? 0 : intval($_REQUEST['pay_method']);
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
        echo "<script type=text/javascript>alert('修改成功！');window.location.href='cash.php';</script>";
    }
    else
    {
        echo "<script type=text/javascript>alert('修改失败！');window.history.back(-1);</script>";
    }
    
    exit(0);
}
else if($act == "delete")
{
    $id = intval($_REQUEST['id']);
    
    if( !CheckCashStreamPrivilege($id, $_SESSION['gp_id']) )
    {
        die("privilege error!");
    }
    
    if( $result = DeleteCashStream($id, $_SESSION['gp_id']) )
    {
        echo "<script type=text/javascript>alert('删除记录成功！');window.location.href='cash.php';</script>";
    }
    else
    {
        echo "<script type=text/javascript>alert('删除记录失败！');window.history.back(-1);</script>";
    }
    
    exit();
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
    if( !isset($_REQUEST['date']) ||  $_REQUEST['date'] == '' )
    {
        echo "<script type=text/javascript>alert('日期不能为空！111');window.history.back(-1);</script>";
        exit();
    }
    
    if( !isset($_REQUEST['type']) || $_REQUEST['type'] == '' )
    {
        echo "<script type=text/javascript>alert('收支类型不能为空！');window.history.back(-1);</script>";
        exit();
    }
    
    if( !isset($_REQUEST['category']) || $_REQUEST['category'] == '' )
    {
        echo "<script type=text/javascript>alert('分类不能为空！');window.history.back(-1);</script>";
        exit();
    }
    
    if( !isset($_REQUEST['sum']) || $_REQUEST['sum'] == '' )
    {
        echo "<script type=text/javascript>alert('金额不能为空！');window.history.back(-1);</script>";
        exit();
    }
    
    if( !isset($_REQUEST['pay_method']) || $_REQUEST['pay_method'] == '' )
    {
        echo "<script type=text/javascript>alert('支付方式不能为空！');window.history.back(-1);</script>";
        exit();
    }
    
    if( !CheckCatPrivilege(intval($_REQUEST['category']), $_SESSION['gp_id']) )
    {
        echo "<script type=text/javascript>alert('非法分类请求！');window.history.back(-1);</script>";
        exit();
    }
    
    return true;
}



