<?php
namespace Home\Controller;
use Think\Controller;
class ShopController extends BaseController{
    //购买商品
    public function goodDetail(){       //商品详情  需传入商品id(goodId)
        $input = I('get.');
        $goodId = $input['goodId'];
        $goodData = D('Good')->where(array('id'=>$goodId))->find();
        $goodData['Price'] = explode('.',$goodData['price']);
        if($goodData){
            jsonpReturn('1','查询成功',$goodData);
        }else{
            jsonpReturn('0','查询失败');
        }
    }

    public function submitGoodOrder(){        //商品提交订单页面,   需传入商品id , 地址id(不传则选择默认)
        $input = I('get.');
        $userId = session('home_user_id');
        $addrId = $input['addrId'];
        $goodId = $input['goodId'];
        if($addrId){
            $where['id'] = $addrId;
        }else{
            $where['u2id'] = $userId;
            $where['default'] = 1;
        }
        $addrData = D('Address')->where($where)->find();
        $goodData = D('Good')->where(array('id'=>$goodId))->find();
        $goodData['Price'] = explode('.',$goodData['price']);
        if($goodData){
            jsonpReturn('1','查询成功',array('goodData'=>$goodData,'addrData'=>$addrData));
        }else{
            jsonpReturn('0','查询失败');
        }
    }

    public function  goodBuyNow(){     //商品提交订单
        $input = I('get.');
        $goodId = $input['goodId'];
        $addressId = $input['addressId'];
        if(empty($addressId)){
            jsonpReturn('0','请填写收货地址');
        }
        $goodNum = $input['goodNum'];
        $userId = session('home_user_id');
        $goodData = D('Good')->where(array('id'=>$goodId))->find();

        $good['ordercode'] = $this->createCode();
        $good['u2id'] = $userId;
        $good['money'] = $goodData['price'];
        $good['message'] = '购买乐器';
        $good['addressid'] = $addressId;
        $good['goodprice'] = $goodData['price'] * $goodNum;
        $good['goodid'] = $goodData['id'];
        $good['create_at'] = time();
        $res = D('Order')->add($good);
        if($res){
            jsonpReturn('1','提交订单成功',$res);
        }else{
            jsonpReturn('1','提交订单失败');
        }
    }

    //购买课程
    public function submitCourseDetail(){       //课程提交订单订单页面      需传入课程id(courseId)
        $input = I('get.');
        $course = D('CourseRelation');
        $userId = session('home_user_id');
        $courseId = $input['courseId'];
        $courseData = $course->where(array('id'=>$courseId))->relation('user1')->find();
        if($courseData){
            jsonpReturn('1','查询成功');
        }else{
            jsonpReturn('0','查询失败');
        }
    }

    public function courseBuyNow(){       //课程提交订单   需传入课程id
        $userId = session('home_user_id');
        $course = D('Course');
        $order = D('Order');
//        $user = D('User');
        $input = I('get.');
        $courseId = $input['courseId'];
//        $paytype = $input['paytype'];
        $courseData = $course->where(array('id'=>$courseId))->find();
        $cour['ordercode'] = $this->createCode();
        $cour['u2id'] = $userId;
//        $cour['money'] = -$courseData['price'];
        $cour['status'] = 1;
        $cour['message'] = '购买课程';
//        $cour['paytype'] = $paytype;
        $cour['courseid'] = $courseId;
        $cour['goodprice'] = $courseData['price'];
        $res = $order->add($cour);
        if($res){
            jsonpReturn('1','提交订单成功',$res);
        }else{
            jsonpReturn('1','提交订单失败');
        }
    }


    public function createCode()//生成订单号
    {
        $Code = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderCode = $Code[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderCode;
    }

    public function showPayment(){   //立即支付页面
        $orderId = I('get.orderId');
        $orderData = D('Order')->where(array('id'=>$orderId))->find();
        if($orderData){
            jsonpReturn('1','查询成功',$orderData);
        }else{
            jsonpReturn('0','查询失败');
        }
    }




    // public function paymentNow(){      //立即支付   需传入payType(1微信支付2支付宝支付3余额支付),订单orderId
    //     $input = I('get.');
    //     $paymentType = $input['payType'];
    //     $orderId = $input['orderId'];
    //     switch($paymentType){
    //         case '1':
    //             $url = "http://".C('WEB').'/home/Wechat/wechatPay/orderId/'.$orderId;
    //             Header("Location: $url");
    //             break;
    //         case '2':
    //             $aliPay = A('AliPay');       //调取支付宝支付
    //             redirect($aliPay->webPay($orderId));
    //             break;
    //         case '3':
    //             $balance = A('Balance');     //调取余额支付
    //             $payVal = $balance->balancePay($orderId);
    //             jsonpReturn1($payVal['0'],$payVal['1']);
    //             break;
    //     }

    // }


    public function index(){
        dump(session('home_user_id'));

        dump(session('children_id'));
    }




}



?>