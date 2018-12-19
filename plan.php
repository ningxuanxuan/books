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
elseif ($act == 'ajaxupdate')
{
    header("Content-type:application/json");
    $response = array('status' => 0, 
                       'ExtraData' => array() );
    
    if (!isset($_REQUEST['cat_id']) || !isset($_REQUEST['value']))
    {
        $response['status'] = 1;
        echo json_encode($response);
        exit(0);
    }
    
    $cat_id = intval($_REQUEST['cat_id']);
    $value  = intval($_REQUEST['value']);
    if(!UpdateSinglePlan($_SESSION['gp_id'], $cat_id, $_SESSION['user_id'], $value))
    {
        $response['status'] = 2;
    }
    else 
    {
        $response['ExtraData']['value'] = $value;
    }
    
    echo json_encode($response);
    exit(0);
}
elseif ($act == 'ajaxquerylimit')
{
    $response = array('status' => 0,
        'ExtraData' => array() );
    
    if ( !isset($_REQUEST['cat_id']) )
    {
        $response['status'] = 1;
        echo json_encode($response);
        exit(0);
    }
    
    $cat_id = intval($_REQUEST['cat_id']);

    $value = GetSinglePlanSum($_SESSION['gp_id'], $cat_id);

    $response['ExtraData']['value'] = $value;
    echo json_encode($response);
    exit(0);
}