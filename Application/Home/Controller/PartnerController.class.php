<?php
namespace Home\Controller;
use Think\Controller;
class PartnerController extends Controller{

    public function doubleDetail(){        //教师详情/机构详情   需传入教师id或者机构id(doubleId)
        $input = I('get.');
        $doubleId = $input['doubleId'];
        $teacherId = 2;
        $user = D('User1Relation')->where(array('id'=>$teacherId))->relation(true)->find();
        if(empty($user['user1'])){
            unset($user['user1']);
        }
        if($user){
            jsonpReturn('1','成功',$user);
        }else{
            jsonpReturn('0','失败');
        }
    }

    public function courseDetail(){           //课程详情   需传入课程id(courseId)
        $input = I('get.');
        $courseId = $input['courseId'];
        $courseId = 2;
        $course = D('CourseRelation')->where(array('id'=>$courseId))->relation('user1')->find();
        if($course){
            jsonpReturn('1','成功',$course);
        }else{
            jsonpReturn('0','失败');
        }
    }

    public function musicShop(){             //音乐商城
       $goodtype = D('Goodtype');
        $goodtypeData = $goodtype->select();
        foreach($goodtypeData as $k=>$v){
            if($v['type'] == '1' ){
                $brand[$k] = $v;
            }else if($v['type'] == '2'){
                $instrument[$k] = $v;
            }else if($v['type'] == '3'){
                $material[$k] = $v;
            }
        }
        if($brand && $instrument && $material){
            $data = array('brand'=>$brand,'instrument'=>$instrument,'material'=>$material);
            jsonpReturn('1','成功',$data);
        }else{
            jsonpReturn('0','失败');
        }

    }

    public function searchMusicShop(){        //音乐商城搜索
        $input = I('get.');
        if($input){
            $brandId = $input['brandId'];
            $instrumentId = $input['instrumentId'];
            $materialId = $input['materialId'];
            if($brandId){
                $where['brand_id'] = $brandId;
            }
            if($instrumentId){
                $where['instrument_id'] = $instrumentId;
            }
            if($materialId){
                $where['material_id'] = $materialId;
            }
            $goodData = D('Good')->where($where)->select();
            if($goodData){
                jsonpReturn('1','成功',$goodData);
            }else{
                jsonpReturn('0','失败');
            }
        }
    }




//    个人中心页面

    public function myCollection(){      //我的收藏
        $userId = session('home_user_id');
        $collection = D('Collection');
        $collectionData = $collection->where(array('u2id'=>$userId))->select();
        if($collectionData){
            jsonpReturn('1','查询成功',$collectionData);
        }else{
            jsonpReturn('0','查询失败');
        }
    }

    public function login(){            //登陆页面  若获取到信息, 需显示用户名,用户密码,保存密码选中状态
        $user = D('User2');
        if(!empty(cookie('mobile')) && !empty(cookie('pwd')) ){
            $mobile = cookie('mobile');
            $pwd = md5(cookie('pwd'));
            $userData = $user->where(array('phone'=>$mobile,'password'=>$pwd))->find();
            if($userData){
                session('home_user_id',$userData['id']);
                jsonpReturn('1','登陆成功',$userData);
            }else{
                jsonpReturn('0','账号不存在或密码错误!');
            }
        }else{
            jsonpReturn('0','失败');
        }
    }


    public function actLogin(){           //点击登陆操作      需传入手机号mobile,用户密码pwd,如果点击保存密码,需传入isSave =1
        $input = I('get.');
        $user = D('User2');
        if($input){
            $mobile = $input['mobile'];
            $pwd = md5($input['pwd']);
            $isSave = $input['isSave'];
            $userData = $user->where(array('phone'=>$mobile,'password'=>$pwd))->find();
            if($userData){
                if($isSave == '1'){
                    cookie('mobile',$userData['phone']);
                    cookie('pwd',$userData['password']);
                }
                session('home_user_id',$userData['id']);
                jsonpReturn('1','登陆成功');
            }else{
                jsonpReturn('0','账号不存在或密码错误!');
            }

        }
    }

    public function sentMsg(){          //点击发送短信验证码   需传入接收验证码的手机号
        $mobile=I('get.mobile');

        $res=D('Msg')->sendMsg($mobile);

        if($res){
            jsonpReturn('1','短信验证码已经发送');
        }else{
            jsonpReturn('0','短信验证码发送失败');
        }

    }




    public function register(){          //注册页面   需传入class(3教师,2机构,1家长,0成人) 省份province  城市city  区/县area
        $input = I('get.');               //手机号phone 密码password  验证码myCode  推荐人手机号refereeMobile
        $user = D('User2');
        $msgCode = cookie('msgCode');
        if($input){
            $code = $input['myCode'];
            $class = $input['class'];
            $province = $input['province'];
            $city = $input['city'];
            $area = $input['area'];
            $phone = $input['phone'];
            $password = $input['password'];
            $refereeMobile = $input['refereeMobile'];
            if($code == $msgCode){
                if(empty($province) || empty($city) || empty($area) ){
                    jsonpReturn('0','地区不能为空');
                }
                if(empty($phone)){
                    jsonpReturn('0','手机号码不能为空');
                }else if(!preg_match("^1[3|4|5|7|8][0-9]{9}$",$phone)){
                    jsonpReturn('0','请输入正确的手机号码');
                }
                if(empty($refereeMobile)){
                    jsonpReturn('0','推荐人手机号不能为空!');
                }else if(!$user->where(array('phone'=>$refereeMobile))->count()){
                    jsonpReturn('0','推荐人手机号不存在!');
                }
                if(!$user->create($input)){
                    jsonpReturn('0',$user->getError());
                }else{
                    $res = $user->add();
                    jsonpReturn('1','注册成功');
                }
            }else{
                jsonpReturn('0','验证码错误!');
            }
        }else{
            jsonpReturn('0','数据错误!');
        }
    }


    public function findPwd(){                      //找回密码
        $input = I('get.');
        $msgCode = cookie('msgCode');
        if($input){
            $code = $input['myCode'];
            $phone = $input['phone'];
            $pwd = md5($input['password']);
            if($msgCode == $code){
                if(empty($phone)){
                    jsonpReturn('0','手机号码不能为空');
                }else if(!preg_match("^1[3|4|5|7|8][0-9]{9}$",$phone)){
                    jsonpReturn('0','请输入正确的手机号码');
                }
                $res = D('User2')->where(array('phone'=>$phone))->setField('password',$pwd);
                if($res){
                    jsonpReturn('1','修改成功',$res);
                }else{
                    jsonpReturn('0','修改失败');
                }
            }else{
                jsonpReturn('0','验证码错误');
            }
        }else{
            jsonpReturn('0','数据错误');
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


    public function mySubData(){    //我的学员 //我的营业额
        $userId = session('home_user_id');
        $turnover = I('get.turnover');          //   需传入 turnover=1
        $user = D('User2');
        $order = D('OrderRelation');
        $userId = 11;
        $course = D('Course');
        $u1id = $user->where(array('id'=>$userId))->getField('u1id');
        $arrId = $course->where(array('user_id'=>$u1id))->getField('id',true);
        $where['courseid']=array('in',$arrId);
        if($turnover == '1'){
            $map['message'] = '基金提现';
            $map['u2id'] = $userId;
            $map['_logic'] = 'and';
            $where['_complex'] = $map;
            $where['_logic'] = 'or';
        }
        $orderData = $order->where($where)->relation(['user2','course'])->select();
        if($turnover == '1'){
            $totalTurnover = 0;
            foreach($orderData as $k=>$v){
                $totalTurnover += $v['goodprice'];
            }
        }
        if($orderData){
            jsonpReturn('1','查询成功',array($orderData,$totalTurnover));
        }else{
            jsonpReturn('0','查询失败');
        }
    }












}



?>