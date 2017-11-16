<?php
define('IN_BOOKS', true);

require_once 'libs/init.php';

$act = empty($_REQUEST['act']) ? 'list' : $_REQUEST['act'];

if($act == 'list')
{
    
}