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

    public function clinic_add($para = array(), $table = 'customer') {
        if(!$this->has($table,array('cusid' => $para['cusid']))) {
            return $this->insert($table,$para);
        } else {
            return false;
        }
    }

    public function clinic_update($para = array(), $where = array(), $table = 'customer') {
        return $this->update($table,$para,$where);
    }

    public function clinic_delete($where = array(), $table = 'customer') {
        return $this->delete($table,$where);
    }
    public function form_delete($where = array(), $table = 'form') {
        return $this->delete($table,$where);
    }
}
