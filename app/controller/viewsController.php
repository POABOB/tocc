<?php
namespace app\controller;
if ( ! defined('PPP')) exit('非法入口');
use app\model\viewsModel;
/**
 * 處理視圖控制器
 *
 */
class viewsController extends \core\PPP {
    //視圖首頁
    public function index() {
        
    }

	//GET 表單視圖
	public function customer() {
        $data = $this->get_customer();
        $this->assign('data', array('cusname' => $data['cusname'], 'cusid' => $data['cusid']));
		$this->display('index.html');
    }
    
    //GET 列表視圖
	public function list() {
        $data = $this->get_customer();
        $this->assign('data', array('cusname' => $data['cusname'], 'cusid' => $data['cusid']));
		$this->display('list.html');
    }
    //獲取customer
    private function get_customer() {
        $cusid = get('cusid') ? get('cusid') : null;
        $database = new viewsModel();
        $data = array();
        $data = $database->get_cus(array('cusname','cusid'), array('cusid' => $cusid));

        if($data == null) {
            $data['cusname'] = null;
            $data['cusid'] = $cusid;
        }
        return $data;
    }
}