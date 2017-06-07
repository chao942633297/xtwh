<?php
namespace Home\Controller;

use Think\Controller;

class UserController extends Controller{
    # 定义控制器使用表名
    const USER    = 'user2';       //用户表
    public function login_test(){
        session('userid',1);
    }

    public function index(){
        $id = session('userid');
        $return = [];
        $user = M(self::USER)->where('id='.$id)->find();
        switch ($user['grade']) {
            case '1':
                $return['grade'] = '路人甲';
                break;
            case '2':
                $return['grade'] = 'vip';
                break;
            case '3':
                $return['grade'] = 'VIP银卡';
                break;
            case '4':
                $return['grade'] = 'VIP金卡';
                break;
            case '5':
                $return['grade'] = 'VIP钻石';
                break;
            case '6':
                $return['grade'] = '合伙人';
                break;
        }
        $parent = M(self::USER)->where('id='.$user['pid'])->find();

        $return['headimg'] = $user['headimg'];
        $return['name'] = $user['name'];
        $return['nickname'] = $user['nickname'];
        if($parent){
            $return['p_name'] = $parent['name'];
            $return['p_nickname'] = $parent['nickname'];
            $return['p_phone'] = $parent['phone'];
        }
        $return['onemoney'] = $user['onemoney'];
        $return['twomoney'] = $user['twomoney'];


        dump($return);
    }
}

/**
 * 获取指定级别后的所有用户
 * @param $uid char 要查询下级的用户id
 * @param $num int   要查的级别后
 * @return 查询指定级别后的用户下级
 */
function getChildenAll($uid,$userall = ''){
	if(empty($userall)){
		static $userall = [];
	}else{
		static $userall = [];
		$userall = [];
	}
		$threeChilden = array();

    if(!in_array($uid, $userall)) {
        array_push($userall, $uid);
    }

    $where['pid'] = ['in',"$uid"];
    $userChilden = M(self::USER)->field('id')->where($where)->select();
    $userChilden = array_column($userChilden, 'id');

    $userall = array_unique(array_merge($userall, $userChilden));

    foreach($userChilden as $user_id) {
        getChildenAll($user_id);
    }

    $threeChildenEnd = array_diff($userall,$threeChilden);
    array_shift($threeChildenEnd);
    return $threeChildenEnd;
}

/**
 * 获取指定级别下级
 * @param $uid char 要查询下级的用户集合id；如'1,2,3'
 * @param $num int   要查询的级别
 * @return 查询级别的用户下级
 */
function getChilden($uid,$num = 1){

    $where['pid'] = ['IN',"$uid"];
    $user1 = M(self::USER)->where($where)->select();
    $users_id = '';
    foreach($user1 as $k=>$v){
        $users_id .= $v['id'].',';
    }
    $users_id = trim($users_id,',');    //一级下级
    for($i = 1;$i < $num;$i++){
        $users_id = getChilden($users_id,$num-1);
        return $users_id;
    }
    return $users_id;
}
