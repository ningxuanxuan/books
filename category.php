<?php
define('IN_BOOKS', true);

require_once 'libs/init.php';

//todo:encode
if( empty($_SESSION['user_id']) || empty($_SESSION['gp_id']) )
{
    $target = "login.php?refer=";
    $target .= empty($_SERVER['HTTPS']) ? "http://" : "https://". $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']   ;
    echo "<script type=text/javascript>window.location.href='$target';</script>";
    exit;
}

reg_common_smarty_vars($smarty);
$user_id = $_SESSION['user_id'];
$group_id = $_SESSION['gp_id'];


$sql = "select * from category where gp_id = $_SESSION[gp_id]";

$result = $db->query( $sql );

$categories = array();

if( $result = $db->query( $sql ) )
{
    while($row = $result->fetch_assoc())
    {
        $categories[] = $row;
    }
}

$smarty->assign('title', '分类管理');
$smarty->assign('categories', $categories);
$smarty->display('category.html');