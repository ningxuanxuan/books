<?php
/**
 * 全新版在线记账及数据分析工具
 * $author : ningxuan
 * $init date : 2017-10-23
 * 
 */

define('IN_BOOKS', true);

require_once('libs/init.php');

if( empty($_SESSION['user_id']) || empty($_SESSION['gp_id']) )
{
    $target = "login.php?refer=";
    $refer = empty($_SERVER['HTTPS']) ? "http://" : "https://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']   ;
    $target .= urlencode($refer);
    echo "<script type=text/javascript>window.location.href='$target';</script>";
    exit;
}

header("Location:dashboard.php");
exit();

?>