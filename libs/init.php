<?php
/***********************************************
 * 
 * 
 ***********************************************/
$start_time = microtime(true);
defined('IN_BOOKS') or die('forbidden access!');

//取得当前所在的根目录
if (__FILE__ == '')
{
    die('Fatal error, empty FILE');
}

define('ROOT_PATH', str_replace('libs/init.php', '', str_replace('\\', '/', __FILE__)));
//配置环境
@ini_set('memory_limit',          '64M');
@ini_set('session.cache_expire',  180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies',   1);
@ini_set('session.auto_start',    0);
@ini_set('display_errors',        1);

//载入配置
require_once(ROOT_PATH . 'data/config.php');
require_once (ROOT_PATH . 'libs/libbase.php');

session_start();
//SSL 跳转
if( $config['force_ssl'] && empty($_SERVER['HTTPS']))
{
    header ("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}

//创建数据库对象
require_once(ROOT_PATH . 'libs/lib_mysql.php');
$db = new cls_mysql($config['db_host'],
                 $config['db_user'],
                 $config['db_passwd'],
                 $config['db_name'],
                 $config['db_port']);

$db->query("set names $config[charset]");

//创建smarty对象
require_once(ROOT_PATH	.	'libs/smarty/Smarty.class.php');
$smarty	=	new Smarty();
$smarty->setTemplateDir( ROOT_PATH	.	$config['template_dir'] );
$smarty->setCompileDir( ROOT_PATH	.	$config['compile_dir'] );
$smarty->setCacheDir( ROOT_PATH	.	$config['cache_dir'] );
$smarty->setConfigDir( ROOT_PATH   .   $config['smarty_config'] );

//注册公共变量
$smarty->assign('theme_root', 'templates/default');
//过滤请求字符串

addslashes_deep($_REQUEST);
addslashes_deep($_GET);
addslashes_deep($_POST);

?>