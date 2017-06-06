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
/**
 * 获取指定级别后的所有用户
 * @param $uid char 要查询下级的用户id
 * @param $num int   要查的级别后
 * @return 查询指定级别后的用户下级
 */
function getThreeEnd($uid,$n = ''){
    static $user = [];
    if($n){
        $threeChilden = '';
        for($i = 1;$i <= $n;$i++){
            $threeChilden .= getChilden($uid,$i).',';
        }
        $threeChilden = explode(',',trim($threeChilden,','));
    }
    if(!in_array($uid, $user)) {
        array_push($user, $uid);
    }

    $where['pid'] = ['in',"$uid"];
    $userChilden = M('user')->field('id')->where($where)->select();
    $userChilden = array_column($userChilden, 'id');

    $user = array_unique(array_merge($user, $userChilden));

            // dump($user);exit;
    foreach($userChilden as $user_id) {
        getThreeEnd($user_id);
    }

    $threeChildenEnd = array_diff($user,$threeChilden);
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
    $user1 = M('user')->where($where)->select();
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