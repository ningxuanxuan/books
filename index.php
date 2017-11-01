<?php
/**
 * 全新版在线记账及数据分析工具
 * $author : ningxuan
 * $init date : 2017-10-23
 * 
 */

define('IN_BOOKS', true);

require_once('libs/init.php');

if( $result = $db->query("select * from books.admins limit 1") )
{
    $row = $result->fetch_assoc();

    $smarty->assign('id', $row['id']);
    $smarty->assign('name', $row['name']);
    $smarty->assign('passwd', $row['passwd']);
    $smarty->assign('status', $row['status']);
    $smarty->assign('last_ip', $row['last_ip']);
    $smarty->assign('last_time', $row['last_time']);
}
else
{
    $smarty->assign('id', $db->error);
}

$smarty->assign('sitename', 'books');
$smarty->assign('title', 'welcome to books');
$smarty->display('index.html');


?>