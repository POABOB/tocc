<?php
namespace app\controller;
if ( ! defined('PPP')) exit('非法入口');
use app\model\apiModel;
use core\lib\resModel;
use core\lib\Validator;
/**
 * 處理api控制器
 *
 */
class apiController extends \core\PPP {
	//GET 獲取列表
	public function list() {
        //獲取$_GET['cusid'] $_GET['month']
        $cusid = get('cusid') ? get('cusid') : null;
        $Sdate = get('month') ? get('month') : date("Y-m-d");
        $Edate = $Sdate." 23:59:59";
        if(strtotime ($Sdate) <= strtotime(date('Y-m-d')) || strtotime ($Sdate) >= strtotime ('-60 days', strtotime(date('Y-m-d')))) {
            $database = new apiModel();
            $data = $database->find(
                '*', 
                array(
                    'cusid' => $cusid,
                    'created_at[>=]' => $Sdate,
                    'created_at[<=]' => $Edate,
                    'ORDER' => array('created_at' => 'DESC')
                )
            );
    
            json(new resModel(200, $data));
            return;
        } else {
            json(new resModel(401, '日期超出60天範圍'));
			return;
        }
    }
    
    //POST 新增表單內容
	public function add() {
        $cusid = get('cusid') ? get('cusid') : null;
        $post = post_json();
        $database = new apiModel();

        //表單驗證
        $v = new Validator();
        $v->validate(
            array(
                '姓名' => $post['name'], 
                '手機號碼' => $post['cellphone'],
                '身份' => $post['identity'],
                '居住地' => $post['residence'],
                '旅遊史' => $post['travel_histroy'],
                '職業別' => $post['occupation'],
                '出入場所' => $post['contact_history'],
                '群聚史' => $post['cluster']
            ),
            array(
                '姓名' => array('required', 'maxLen' => 128),
                '手機號碼' => array('required', 'maxLen' => 15),
                '居住地' => array('required', 'maxLen' => 256),
                '身份' => array('required', 'maxLen' => 3),
                '旅遊史' => array('required'),
                '職業別' => array('required'),
                '出入場所' => array('required'),
                '群聚史' => array('required'),
            )
        );
		if($v->error()) {
			json(new resModel(401, $v->error(), '提交格式有誤'));
			return;
		}

        //勾選規則
        if(intval($post['travel_histroy']) == 1 && $post['travel_country'] == null) {
            json(new resModel(401, '請填寫國家!'));
            return;
        } else if(intval($post['travel_histroy']) == 2 && $post['travel_destination'] == null) {
            json(new resModel(401, '請填寫旅遊地!'));
            return;
        } else if(intval($post['occupation']) == 6 && $post['occupation_other'] == null) {
            json(new resModel(401, '請填寫職業!'));
            return;
        } else if(in_array(4, $post['contact_history']) == 1 && $post['contact_multi'] == null) {
            json(new resModel(401, '請勾選參與的集會!'));
            return;
        } else if(in_array(6, $post['contact_history']) == 1 && $post['contact_other'] == null) {
            json(new resModel(401, '請填寫出入場所!'));
            return;
        } else if(in_array(1, $post['cluster']) == 1 && $post['cluster_multi'] == null) {
            json(new resModel(401, '請勾選同住家人狀態!'));
            return;
        } else if(intval($post['cluster_multi']) == 2 && $post['cluster_date'] == null) {
            json(new resModel(401, '請填寫到期日!'));
            return;
        }

        //資料處理
        $return = $database->add(
            array(
                "name" => $post["name"],
                "cellphone" => $post["cellphone"],
                "residence" => $post["residence"],
                "identity" => $post["identity"], //0:就診, 1:陪同, 2:其他, 3屏東枋山
                "identity_other" => $post["identity_other"], //旅遊史備註
                "travel_histroy" => $post["travel_histroy"], //0:無, 1=> 曾出國
                "travel_country" => $post["travel_country"], //出國的國家名稱, history為1時必填
                "travel_destination" => $post["travel_destination"], //旅遊地, history為2時必填
                "occupation" => $post["occupation"],//0:無, 1,2,3,4,5,6=> 其他
                "occupation_other" => $post["occupation_other"],//其他職業(可null, 其他被勾選擇必填)
                "contact_history" => json_encode($post["contact_history"]), //接觸史 0:無, 1,2,3,4(如果勾選，則contact_multi裡頭加入),5,6:其他
                "contact_multi" => $post["contact_multi"], //0,1
                "contact_other" => $post["contact_other"],//其他接觸史(可null, 其他被勾選擇必填)
                "cluster" => json_encode($post["cluster"]),//群聚 0:無, 1(如果勾選，則cluster_multi裡頭加入),2,3,4
                "cluster_multi" => $post["cluster_multi"],//0,1,2
                "cluster_date"=> $post["cluster_date"],//自主健康管理日期(yyyy-mm-dd) 如果cluster為2時必填
                "ip_addr" => get_IP(),
                "cusid" => $cusid
            )
        );

		if($return) {
			json(new resModel(200, '表單提交成功!'));
			return;
		}
		json(new resModel(400, '請確認id是否正確!'));
    }
    
    //POST 統計登入api
    public function login() {
        $cusid = get('cusid') ? get('cusid') : null;
        $post = post_json();
        if('leyan' . $cusid == $post['account']) {
            $_SESSION['user'] = $post['account'];
            json(new resModel(200, '登入成功!'));
            return;
        }
        json(new resModel(400, '請確認登入碼是否正確!'));
    }
    
    //POST 後台登入api
    public function clinic_login() {
        $post = post_json();
        if($post['account'] === 'leyan520') {
            $_SESSION['user'] = true;
            json(new resModel(200, '登入成功!'));
            return;
        }
        json(new resModel(400, '請確認登入碼是否正確!'));
    }

    //POST 新增診所api
    public function clinic_add() {
        $post = post_json();
        $database = new apiModel();
        $return = $database->clinic_add(
            array(
                "cusname" => $post["cusname"],
                "custel" => $post["custel"],
                "cusaddr" => $post["cusaddr"],
                "cusid" => $post["cusid"],
            )
        );
        if($return) {
            json(new resModel(200, '新增診所成功!'));
            return;
        }
        json(new resModel(400, '請勿重複新增相同ID!'));
    }

    //POST 更新診所api
    public function clinic_update() {
        $post = post_json();
        $database = new apiModel();
        $return = $database->clinic_update(
            array(
                "cusname" => $post["cusname"],
                "custel" => $post["custel"],
                "cusaddr" => $post["cusaddr"]
            ),
            array("cusid" => $post["cusid"])
        );
        if($return) {
            json(new resModel(200, '更新診所成功!'));
            return;
        }
        json(new resModel(400, '更新診所失敗!'));
    }
    //POST 刪除診所api
    public function clinic_delete() {
        $post = post_json();
        $database = new apiModel();
        $database->form_delete(
            array("cusid" => $post["cusid"])
        );
        $return = $database->clinic_delete(
            array("cusid" => $post["cusid"])
        );
        if($return) {
            json(new resModel(200, '刪除診所成功!'));
            return;
        }
        json(new resModel(400, '刪除診所失敗!'));
    }
}