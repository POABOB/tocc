<?php
/**
 * 入口文件
 * 1. 定義常量
 * 2. 加載涵式庫
 * 3. 啟動框架
 */
//定義根目錄
define('PPP', realpath('./'));
//定義核心文件目錄
define('CORE', PPP.'/core');
//定義app目錄
define('APP', PPP.'/app');
//定義app常量
define('MODULE','app');
define('URL','');
define('HTTP','http://');
//DEBUG模式開關
define('DEBUG', true);

include 'vendor/autoload.php';

if(DEBUG) {
	$whoops = new \Whoops\Run;
	$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
	$whoops->register();
    ini_set('display_error', 'On');
    error_reporting(E_ALL | E_STRICT);
} else {
	ini_set('display_error', 'Off');
}
//避免cookie被client修改
ini_set("session.cookie_httponly", 1); 
// ini_set("memory_limit","128M");
//加載涵式庫
include CORE.'/common/function.php';
include CORE.'/ppp.php';

//自動加載
spl_autoload_register('\core\ppp::load');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers');
date_default_timezone_set("Asia/Taipei");

//啟動框架
\core\ppp::run();
exit();