<?php
namespace Home\Controller;
use Think\Controller;
class AddressController extends Controller{

    public function _initialize(){
        session('home_user_id',15);
    }

    public function addrInfo(){        //地址页面
        $userId = session('home_user_id');
        $address = D('Address');
        $addressData = $address->where(array('u2id'=>$userId))->select();
        if($addressData){
            jsonpReturn('1','查询成功',$addressData);
        }else{
            jsonpReturn('0','查询失败');
        }
    }

    public function showAddr(){      //添加/修改收货地址页面  传入addrId为修改收货地址
        $input = I('get.');
        $address = D('Address');
        $addrId = $input['addrId'];
        if($addrId){
            $addrData = $address->where(array('id'=>$addrId))->find();
            if($addrData){
                jsonpReturn('1','查询成功',$addrData);
            }
        }else{
            jsonpReturn('0','查询失败');
        }
    }


    public function addAddr(){        //新增/修改收货地址   修改地址需传入地址id(addrId). 添加地址 需传入收货人姓名name,收货人手机号phone,省份province, 城市city,区/县area ,
        $input = I('get.');            //街道 street, 邮编zipcode
        $address = D('address');
        $userId = session('home_user_id');
        $addrId = $input['addrId'];
        if($input){
            if(empty($input['phone'])){
                jsonpReturn('0','手机号码不能为空');
            }else if(!preg_match("^1[3|4|5|7|8][0-9]{9}$",$input['phone'])){
                jsonpReturn('0','请输入正确的手机号码');
            }
            if(empty($input['province'])){
                jsonpReturn('0','省份不能为空');
            }else if(empty($input['city'])){
                jsonpReturn('0','城市不能为空');
            }else if(empty($input['area'])){
                jsonpReturn('0','区/县不能为空');
            }else if(empty($input['street'])){
                jsonpReturn('0','街道不能为空');
            }

            if(!$address->create($input)){
                jsonpReturn('0',$address->getError());
            }
            $address->u2id = $userId;
            if($addrId){
                $res = $address->where(array('id'=>$addrId))->save();
            }else{
                $res = $address->add();
            }
            if($res){
                jsonpReturn('1','操作成功',$res);
            }else{
                jsonpReturn('0','操作失败');
            }
        }

    }

    public function delAddr(){  //删除收货地址     需传入地址id(addrId)
        $input = I('get.');
        $address = D('Address');
        if($input){
            $addrId = $input['addrId'];
            $res = $address->where(array('id'=>$addrId))->delete();
            if($res){
                jsonpReturn('1','删除成功');
            }else{
                jsonpReturn('0','删除失败');
            }
        }else{
            jsonpReturn('0','缺少主键');
        }
    }

    public function setDefault(){   //设置默认地址   需传入地址id(addrId)
        $input = I('get.');
        $address = D('Address');
        if($input){
            $addrId = $input['addrId'];
            $res = $address->where(array('id'=>$addrId))->setField(array('default'=>1,'update_at'=>time()));
            if($res){
                $address->where(array('id'=>array('neq',$addrId)))->setField('default','0');
                jsonpReturn('1','设置成功',$res);
            }else{
                jsonpReturn('0','设置失败');
            }
        }else{
            jsonpReturn('0','缺少主键');
        }
    }



}


?>