<?php
namespace Home\Model;


class DirectSeedingModel{

    private static $appKey = 'iey6u5sxi16asnci';
    private static $appSecret = 'AIyQo4Y9HRqbpmzy';
    private static $currencyUrl = 'http://api.quklive.com';
    private static $addMemberUrl = '/cloud/services/user/addMember';

    //文档需要随机数
    private function getNonce(){
        return time().$this->greatRand();
    }

    //生成随机数
    private function greatRand(){
        $str = '1234567890';
        $result = '';
        for($i=0;$i<6;$i++){
            $result .= $str{rand(0,strlen($str)-1)};
        }
        return $result;
    }

    //获取签名
    private function getSignature(){
        $content = 'appKey='.self::$appKey.'&nonce='.self::getNonce();
        $signature = base64_encode(self::hmacsha1($content, self::$appSecret));
        return $signature;
    }

    //签名加密方式

    private function hmacsha1($key,$data) {
        $blocksize=64;
        $hashfunc='sha1';
        if (strlen($key)>$blocksize)
            $key=pack('H*', $hashfunc($key));
        $key=str_pad($key,$blocksize,chr(0x00));
        $ipad=str_repeat(chr(0x36),$blocksize);
        $opad=str_repeat(chr(0x5c),$blocksize);
        $hmac = pack(
            'H*',$hashfunc(
                ($key^$opad).pack(
                    'H*',$hashfunc(
                        ($key^$ipad).$data
                    )
                )
            )
        );
        return $hmac;
    }



    public function addMember($data){
        if($data){
           $data['appKey'] = self::$appKey;
            $data['appSecret'] = self::$appSecret;
            $data['signature'] = self::getSignature();
            $jsonData = json_encode($data);
            $returnVal = self::http_curl(self::$currencyUrl.self::$addMemberUrl,'post','json',$jsonData);
        }
        return $returnVal;

    }

    /*
    * $url 接口url
    * $type 请求类型 string
    * $res 返回数据类型 string
    * $arr post的请求参数 string
    */
    private function http_curl($url, $type = 'get', $res = 'json', $arr = '')
    {
        //1、初始化curl
        $ch = curl_init();
        //2、设置curl的参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//将抓取到的东西返回
        if ($type == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);// 传递一个作为HTTP “POST”操作的所有数据的字符串。
        }
        //3、采集
        $output = curl_exec($ch);

        if ($res == 'json') {
            if (curl_errno($ch)) {
                //请求失败，返回错误信息
                $str=curl_error($ch);
                //4、关闭
                curl_close($ch);
                return $str;
            } else {
                //请求成功
                $json=json_decode($output, true);
                //4、关闭
                curl_close($ch);
                return $json;
            }
        }
    }


}



?>