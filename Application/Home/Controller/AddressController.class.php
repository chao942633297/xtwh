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

    public function addAddr(){        //新增/修改收货地址   修改地址需传入地址id(addrId). 添加地址 需传入收货人姓名name,收货人手机号phone,省份province, 城市city,区/县area ,
        $input = I('get.');            //街道 street, 邮编zipcode
        $address = D('address');
        $userId = session('home_user_id');
        $addrId = $input['addrId'];
        if($input['name']){
            if(!$address->create($input)){
                jsonpReturn('1',$address->getError());
            }
            if($addrId){
                $res = $address->where(array('id'=>$addrId))->save();
            }else{
                $res = $address->add();
            }
            if($res){
                jsonpReturn('1','成功',$res);
            }else{
                jsonpReturn('0','失败');
            }
        }else{
            if($addrId){
                $addrData = $address->where(array('id'=>$addrId))->find();
                if($addrData){
                    jsonpReturn('1','查询成功',$addrData);
                }
            }else{
                jsonpReturn('0','查询失败');
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
            $res = $address->where(array('id'=>$addrId))->setField('default','1');
            if($res){
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