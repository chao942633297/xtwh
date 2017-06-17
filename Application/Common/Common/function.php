<?php
// 判断是否是微信内部浏览器
function is_weixin(){
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
            return false;
}

function ObjectToArray($array) {
    if(is_object($array)) {
        $array = (array)$array;
     } if(is_array($array)) {
         foreach($array as $key=>$value) {
             $array[$key] = ObjectToArray($value);
             }
     }
     return $array;
}
function createCode()//生成订单号
{
    $Code = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
    $orderCode = $Code[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    return $orderCode;
}

/**
 * 获取所有下级
 * @param $uid char 要查询下级的用户id
 * @param $num int
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

    $where['refereeid'] = ['in',"$uid"];
    $userChilden = M('user2')->field('id')->where($where)->select();
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

    $where['refereeid'] = ['IN',"$uid"];
    $user1 = M('user2')->where($where)->select();
    $users_id = '';
    foreach($user1 as $k=>$v){
        $users_id .= $v['id'].',';
    }
    $users_id = trim($users_id,',');    //一级下级
    for($i = 1;$i < $num;$i++){
        if(!$user_id){
            return $user_id;
        }
        $users_id = getChilden($users_id,$num-1);
        return $users_id;
    }
    return $users_id;
}


/**
* 获得用户推广奖
* @param $id char 要查询的用户
* @return 推广金额
*/
function getPromotion($id){
    $time = getTime(time());
    $one_children = M('user2')->where(['refereeid'=>$id,'create_at'=>['between',$time]])->select();
    $two_children = explode(',',getChilden($id,2));
    $three_children = explode(',',getChilden($id,3));
    $money = 0;
    if(count($one_children) >= 10){
        foreach($one_children as $k => $v){
            $money += M('order')->where(['u2id'=>$v,'money'=>['GT',0]])->find()['money'];    //查询order里面所有的充值订单
        }
        foreach($two_children as $k => $v){
            $money += M('order')->where(['u2id'=>$v,'money'=>['GT',0]])->find()['money'];    //查询order里面所有的充值订单
        }
        foreach($three_children as $k => $v){
            $money += M('order')->where(['u2id'=>$v,'money'=>['GT',0]])->find()['money'];    //查询order里面所有的充值订单
        }
    }
    $money = ($money / 100);
    return $money;
}



/**
* 获取指定时间的开始日期和结束日期
* @param $time 时间戳
* @return  array   开始时间和结束时间
*/
function getTime($time){
    $t = date('t',$time);
    $time = date('Y-m',$time);
    $start = $time.'-01 00:00:00';
    // dump($start);
    $stop = $time.'-'.$t.' 24:00:00';
    // dump($stop);
    $return['start'] = strtotime($start);
    $return['stop'] = strtotime($stop);
    return $return;
    // $time =
}

/**
* 晋级规则
* @param $money 订单金额    $id 用户id
* @return success    fail
*/
function promotion($money = 0,$id){
    if(empty($money)){
        return 'fail';
    }
    if(empty($id)){
        $id = session('userid');
    }
    // $id = 14;
    // $money = 900;
    $children = explode(',',getChilden($id));
    $child_money = M('user2')->where(['id'=>['in',$children]])->count('rebate_money');
    $user = M('user2')->where(['id'=>$id])->find();
    $user['rebate_money'] += $money;
    $save['rebate_money'] = $user['rebate_money'];
    if($user['rebate_money'] >= 900 && $user['rebate_money'] < 1800 && $child_money < 36000 || $user['rebate_money'] < 900 && $child_money >= 36000 && $child_money < 72000){
        $save['grade'] = '1';
        M('user2')->where(['id'=>$id])->save($save);
    }elseif($user['rebate_money'] >= 1800 && $user['rebate_money'] < 3600 && $child_money < 36000 || $user['rebate_money'] >= 900 && $user['rebate_money'] < 1800 && $child_money >= 36000 && $child_money < 72000 || $user['rebate_money'] < 900 && $child_money >= 72000 && $child_money < 108000){
        $save['grade'] = '2';
        M('user2')->where(['id'=>$id])->save($save);
    }elseif($user['rebate_money'] >= 3600 && $user['rebate_money'] < 7200 && $child_money < 36000 || $user['rebate_money'] >= 1800 && $user['rebate_money'] < 3600 && $child_money >= 36000 || $user['rebate_money'] >= 900 && $user['rebate_money'] < 1800 && $child_money >= 72000 || $user['rebate_money'] < 900 && $child_money >= 108000){
        $save['grade'] = '3';
        M('user2')->where(['id'=>$id])->save($save);
    }elseif($user['rebate_money'] >= 3600 && $user['rebate_money'] < 7200 && $child_money > 36000){
        $save['grade'] = '4';
        M('user2')->where(['id'=>$id])->save($save);
    }elseif($user['rebate_money'] >= 7200){
        $save['grade'] = '5';
        M('user2')->where(['id'=>$id])->save($save);
    }else{
        $save['grade'] = '0';
        M('user2')->where(['id'=>$id])->save($save);
    }
    return 'success';
}

// 公用函数库
/**
 * 模拟提交参数，支持https提交 可用于各类api请求
 * @param string $url ： 提交的地址
 * @param array $data :POST数组
 * @param string $method : POST/GET，默认GET方式
 * @return mixed
 */
function http($url, $data='', $method='GET'){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        exit(curl_error($ch));
    }

    curl_close($ch);
    // 返回结果集
    return $result;
}
function LetvHttp($url, $data='', $method='GET',$header=['User-Agent'=>'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)']){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        exit(curl_error($ch));
    }
    curl_close($ch);
    // 返回结果集
    return $result;
}

/*发送短信验证码
auth:mpc
$mobile:手机号
$code :验证码
*/
function NewSms($Mobile){
      $str = "1234567890123456789012345678901234567890";
      $str = str_shuffle($str);
      $code= substr($str,3,6);
    $data = "username=%s&password=%s&mobile=%s&content=%s";
    $url="http://120.55.248.18/smsSend.do?";
    $name = "HongRu";
    $pwd  = md5("dI9qE8nK");
    $pass =md5($name.$pwd);
    $to   =  $Mobile;
    $content = "您的验证码是：".$code."，切勿将验证码泄露于他人。【承德鸿儒】";
    $content = urlencode($content);
    $rdata = sprintf($data, $name, $pass, $to, $content);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$rdata);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $result = curl_exec($ch);
    curl_close($ch);
    return ['code' => $code, 'data' => $result, 'msg' => ''];
}


/**
 * [getExcel 导出数据到excel]
 * @传入：[expTitle string excel文件名]
 *       [expCellName string 列名数组]
 *       [expTableData string 数据]
 * @返回：[]
 */
function getExcel($expTitle,$expCellName,$expTableData){
    //$xlsTitle=iconv('utf-8','gb2312',$expTitle);
    $filename=$expTitle.date('_YmdHis');
    $cellNum=count($expCellName);
    $dataNum=count($expTableData);
    Vendor("phpExcel.PHPExcel");
    $objPHPExcel=new \PHPExcel();
    $cellName=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
    for($i=0;$i<$cellNum;$i++){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'1',$expCellName[$i][1]);
    }
    for($i=0;$i<$dataNum;$i++){
        for($j=0;$j<$cellNum;$j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+2), " ".$expTableData[$i][$expCellName[$j][0]]);
        }
    }
    header('pragma:public');
    header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$expTitle.'.xls"');
    header("Content-Disposition:attachment;filename=".$filename.".xls");//attachment新窗口打印inline本窗口打印
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}


    /**
     *
     * 导出Excel -- 例子
     */
    function expUser(){//导出Excel
        $xlsName  = "User";
        $xlsCell  = array(
        array('id','账号序列'),
        array('truename','名字'),
        array('sex','性别'),
        array('res_id','院系'),
        array('sp_id','专业'),
        array('class','班级'),
        array('year','毕业时间'),
        array('city','所在地'),
        array('company','单位'),
        array('zhicheng','职称'),
        array('zhiwu','职务'),
        array('jibie','级别'),
        array('tel','电话'),
        array('qq','qq'),
        array('email','邮箱'),
        array('honor','荣誉'),
        array('remark','备注')
        );
        $xlsModel = M('Member');

        $xlsData  = $xlsModel->Field('id,truename,sex,res_id,sp_id,class,year,city,company,zhicheng,zhiwu,jibie,tel,qq,email,honor,remark')->select();
        foreach ($xlsData as $k => $v)
        {
            $xlsData[$k]['sex']=$v['sex']==1?'男':'女';
        }
        $this->getExcel($xlsName,$xlsCell,$xlsData);

    }
