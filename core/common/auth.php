<?php
namespace core\common;
if ( ! defined('PPP')) exit('非法入口');
use core\lib\resModel;

class auth
{
    //工廠方法
    public static function factory() 
    { 
        return new self; 
    } 
	//for login.html
	public function authForLogin($msg = 'login not yet') {
		if((isset($_SESSION['user'])))
		{
			json(new resModel(200, $msg));
			exit();
		}
	}

	//user
	public function user($msg = 'login not yet', $ret = 0) {
		if(!(isset($_SESSION['user'])))
		{
			if($ret == 0) {
                json(new resModel(401, $msg));
			    exit();
            } else {
                return 0;
            }
        }
        return 1;
	}

	//admin
	public function admin($msg = 'Permission denied', $right = array(2), $ret = 0) {
		if(!(isset($_SESSION['user']) && in_array($_SESSION['user']['right'] ,$right)))
		{
            if($ret == 0) {
                json(new resModel(403, $msg));
			    exit();
            } else {
                return 0;
            }
        }
        return 1;
    }

    public function base64Validation() {
        if((isset($_GET['id']) && !empty($_GET['id']))) {
            $input = $_GET['id'];
            $remainder = strlen($input) % 4;
            if ($remainder) {
                $addlen = 4 - $remainder;
                $input .= str_repeat('=', $addlen);
            }
            $info = base64_decode(strtr($input, '-_', '+/'));
            
            $tokens = explode('.', $info);
            if (count($tokens) == 2) {
                return $tokens;
            }
        }
        return false;
    }
}