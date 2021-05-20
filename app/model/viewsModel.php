<?php
namespace app\model;
if ( ! defined('PPP')) exit('非法入口');
use core\lib\model;

class viewsModel extends model
{
    //返回一維
    public function get_cus($para = array(), $where = array(), $table = 'customer') {
        return $this->get($table,$para,$where);
    }

    //返回二維，全部
    public function find_cus($para = array(), $where = array(), $table = 'customer') {
        return $this->select($table,$para,$where);
    }
}
