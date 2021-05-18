<?php

if ( ! defined('PPP')) exit('非法入口');

// show msg like var_dump
function p($var)
{
	if (is_bool($var)) {
		var_dump($var);
	} else if (is_null($var)) {
		var_dump(NULL);
	} else {
		echo "<pre>".print_r($var, true)."</pre>";
	}
}

/**
 *@param $name 對應值
 *@param $default 默認值
 *@param $fitt 過濾方法 'int'
 */
function get($name, $default = false, $fitt = false)
{
	if (isset($_GET[$name])) {
		if($fitt) {
			switch ($fitt) {
				case 'int':
					if(is_numeric($_GET[$name])) {
						return $_GET[$name];
					} else {
						return $default;
					}
					break;
				default:
					# code...
					break;
			}
		} else {
			return $_GET[$name];
		}
	} else {
		return $default;
	}
}

// //判斷方法
// function http_method()
// {
//     if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
//         return 'POST';
//     } else {
//         return 'GET';
//     }
// }

//判斷方法
function http_method($method = 'GET')
{
    if (!(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == $method)) {
		show404();
    } 
}

//顯示404
function show404()
{
    header('HTTP/1.1 404 Not Found');
    header("status: 404 Not Found");
    exit();
}

//返回json格式資料
function json($array)
{
    header('Content-Type:application/json; charset=utf-8');
    echo json_encode($array, true);
}

//獲取json
function post_json(){
	$json = file_get_contents('php://input');
	return json_decode($json, true);
}

function base_url($string = '/')
{
	if(strlen($string) != 1) {
		if((substr($string, 0, 1)) != '/') {
			$string = '/'.$string;
		}
	}
	return HTTP.$_SERVER['SERVER_NAME'].URL.$string;
}

function site_url($string = '/')
{
	if(strlen($string) != 1) {
		if((substr($string, 0, 1)) != '/') {
			$string = '/'.$string;
		}
	}
	return HTTP.$_SERVER['SERVER_NAME'].URL.'/app/views/assets'.$string;
}

function get_chinese_weekday($datetime)
{
    $weekday = date('w', strtotime($datetime));
    return '星期' . ['日', '一', '二', '三', '四', '五', '六'][$weekday];
}

function get_weekday($datetime)
{
    $weekday = date('w', strtotime($datetime));
    return ['日', '一', '二', '三', '四', '五', '六'][$weekday];
}
/**
 *@param $name 對應值
 *@param $default 默認值
 *@param $fitt 過濾方法 'int'
 */
 //json 傳遞不需要
// function post($name, $default = false, $fitt = false)
// {
// 	if (isset($_POST[$name])) {
// 		if($fitt) {
// 			switch ($fitt) {
// 				case 'int':
// 					if(is_numeric($_POST[$name])) {
// 						return $_POST[$name];
// 					} else {
// 						return $default;
// 					}
// 					break;
// 				default:
// 					# code...
// 					break;
// 			}
// 		} else {
// 			return $_POST[$name];
// 		}
// 	} else {
// 		return $default;
// 	}
// }

function getweek_fday_lday($thisday){
    //取得thisday 為禮拜幾 0-6
    $weekday = date("w", strtotime($thisday));
    //該週的第一天
    $week_fday = date("Y-m-d", strtotime("$thisday -".$weekday." days"));
    //該週的最後一天
    $week_lday = date("Y-m-d", strtotime("$week_fday +6 days"));
    //回傳 日期,該日期當週的第一天,該日期當週的最後一天
    return array('this_day'=>$thisday,'week_first_day'=>$week_fday,'week_last_day'=>$week_lday);
}

function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();
   
    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

function sortByDate($a, $b) {
    return strtotime($a['date']) - strtotime($b['date']);
}

//像是js的find()
function array_find(array $array, callable $callback) {
    foreach ($array as $key => $value) {
        if ($callback($value, $key, $array)) {
            return $value;
        }
    }
}

function blob_etag($file, $datetime) {
    // ETAG LAST-MODYFIED
    $last_modified  = strtotime($datetime);

    $modified_since = ( isset( $_SERVER["HTTP_IF_MODIFIED_SINCE"] ) ? strtotime( $_SERVER["HTTP_IF_MODIFIED_SINCE"] ) : false );
    $etagHeader     = ( isset( $_SERVER["HTTP_IF_NONE_MATCH"] ) ? trim( $_SERVER["HTTP_IF_NONE_MATCH"] ) : false );

    // This is the actual output from this file (in your case the xml data)
    $content = $file;
    // generate the etag from your output
    $etag = sprintf( '"%s-%s"', $last_modified, md5($content));

    //set last-modified header
    header( "Last-Modified: ".gmdate( "D, d M Y H:i:s", $last_modified )." GMT" );
    //set etag-header
    header( "Etag: ".$etag );
    header('Cache-Control: public');

    // if last modified date is same as "HTTP_IF_MODIFIED_SINCE", send 304 then exit
    if ((int)$modified_since === (int)$last_modified && $etag === $etagHeader ) {
        header( "HTTP/1.1 304 Not Modified" );
        exit;
    }

    header("Content-type: image/jpg;charset=utf-8");
    echo $content;
    exit();
}

function get_IP(){
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
     $cip = $_SERVER["HTTP_CLIENT_IP"];
    }
    elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
     $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    elseif(!empty($_SERVER["REMOTE_ADDR"])){
     $cip = $_SERVER["REMOTE_ADDR"];
    }
    else{
     $cip = "Unknown";
    }
    return $cip;
   }