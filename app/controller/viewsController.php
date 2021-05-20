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

    //function 獲取customer
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

    //GET 登入視圖
    public function login() {
        $this->display('login.html');
    }

    //GET 所有customer列表視圖
    public function clinic_list() {
        //獲取所有診所對應網址
        $database = new viewsModel();
        $data = $database->find_cus('*', 1);
        $this->assign('data', $data);
        $this->display('clinic_list.html');
    }
}