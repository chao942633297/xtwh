<?php

/**
 * 生成订单二维码
 * @param int $id 订单id
 * @param $level 容错等级
 * @param $size 图片大小
 * @return
 */
function qrcode($id,$level=3,$size=4){
   Vendor('phpqrcode.phpqrcode');
   $name   = 'qrcode'.$id;
   $path = "./Uploads/qrcode/$name.png";
   $errorCorrectionLevel =intval($level) ;//容错级别
   $matrixPointSize = intval($size);//生成图片大小
   //生成二维码图片
   $object = new \QRcode();
   $url = 'http://'.$_SERVER['HTTP_HOST'].'/Home/Wechat/code.html?pid='.$id;
   // $url = "http://hr2.hongrunet.com/html/jyt_register.html?pid=".$id;
   $img = $object->png($url,$path, $errorCorrectionLevel, $matrixPointSize, 2,false);
}


/**
 * Jsonp方式返回数据到客户端
 * @param mixed $data 要返回的数据
 * @author jcl
 * @return array
 */
function jsonpReturn($status='',$msg='',$data=array()) {
	$data = array("status"=>$status,"msg"=>$msg,"data"=>$data);
    if(empty($type)) $type  =   'jsonp';
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:application/json; charset=utf-8');
            $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
            exit($handler.'('.json_encode($data,$json_option).');');
}
