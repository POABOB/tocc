<?php

namespace core\lib;

if ( ! defined('PPP')) exit('非法入口');

use core\lib\config;

class route
{
	//file path
	// public $file = $_SERVER['SERVER_NAME'].URL.'/views/assets/';
	//控制器
	public $controller;
	//方法
	public $action;

	public static function _path($path)
	{
		$path_elements = explode("/", $path);
		$path = "";
		if (isset($path_elements[2])){
			for ($i = 2 ;$i < count($path_elements); $i++ )
				$path .= "/".$path_elements[$i];
		}
		return $path;
	}
	public function __construct() {
		// p('route ok');
		//xxx.com.tw/index/myfriend/
		/**
		 * 1. 隱藏index.php
		 * 2. 獲取URL，參數部分
		 * 3. 反回對應控制器和方法
		 */

		
		//$path = $_SERVER['PATH_INFO'];
		/**
		 *  with diif apache config or php config
		 *  it could be a null when you tried to get $_SERVER['PATH_INFO']
		 * so the way below is to make this variable manually without system's help
		*/
		$path = $this->_path($_SERVER['REQUEST_URI']);
		// echo $path;
		// echo $_SERVER['PATH_INFO'];
		//***********************************************************

		//先給控制器和方法index，減少判斷次數
		$this->controller = config::get('CONTROLLER', 'route');
		$this->action = config::get('ACTION', 'route');


		//
		//如果URL不存在和不是/，則返回index
		/**
		 * 處理資料夾影響控制器和方法有兩種方式:
		 * 1. 指定虛擬域名在PPP的資料夾(X)
		 * 2. 使用$_SERVER['PATH_INFO'](O)
		 */
		//p($_SERVER['PATH_INFO']);
		if(isset($path) && $path != '/') {

			$path_array = explode('/', trim($path, '/'));
			//判斷控制器
			if(isset($path_array[0])) {
				$this->controller = $path_array[0];
				//存在控制器，讓其從array消失
				unset($path_array[0]);
			}

			//判斷方法
			if(isset($path_array[1])) {
				$this->action = $path_array[1];
				//存在方法，讓其從array消失
				unset($path_array[1]);
			}

			//url多餘的值轉換成 GET 參數
			//index/id/1/2/3/
			//剩餘的array則是參數
			//分類
			//array的index從2開始
			$count = count($path_array) + 2;
			$i = 2;
			while($i < $count) {
				//如果沒有對應值，跳出
				if(isset($path_array[$i + 1])) {
					$_GET[$path_array[$i]] = $path_array[$i + 1];
				}
				$i = $i + 2;
			}
			// p($_GET);
		}
	}
}