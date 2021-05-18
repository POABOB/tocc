<?php
namespace app\model;
if ( ! defined('PPP')) exit('非法入口');
use core\lib\model;
class apiModel extends model {
    public function find($para = array(), $where = array(), $table = 'form') {
        return $this->select($table,$para,$where);
    }

    public function add($para = array(), $table = 'form') {
        if($this->has('customer', array('cusid' => $para['cusid']))) {
            $this->insert($table,$para);
            return $this->id();
        } else {
            return false;
        }
    }
}
