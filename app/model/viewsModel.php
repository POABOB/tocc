<?php
namespace app\model;
if ( ! defined('PPP')) exit('非法入口');
use core\lib\model;

class viewsModel extends model
{
    //返回二維，全部
    public function get_cus($para = array(), $where = array(), $table = 'customer') {
        return $this->get($table,$para,$where);
    }
}
