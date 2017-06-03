<?php
namespace Service;

/* 工具类 微信消息的接收回复  */
class WechatRe{

	//实现valid验证方法：实现对微信公众平台的对接
	public function valid()
    {
		//接受随机字符串
        $echoStr = $_GET["echostr"];
		//进行用户数字签名验证
        if($this->checkSignature()){
			header("content-type:text");
        	echo $echoStr;//如果成功，则返回接收到的随机字符串
        	exit;
        }
    }
	private function checkSignature()
	{
		//判断token是否定义
      /*  if (!defined("TOKEN")) {
			//如果没有定义报错
            throw new Exception('TOKEN is not defined!');
        }*/
        //接收微信加密签名
        $signature = $_GET["signature"];
		//接收时间戳
        $timestamp = $_GET["timestamp"];
		//接收随机数
        $nonce = $_GET["nonce"];
        //赋值		
		$token = C('Wechat')['TOKEN'];
		//把相关参数组成数组（密钥、时间戳、随机数）
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		//通过字典法进行排序
		sort($tmpArr, SORT_STRING);
		//把排序后的数组转化为字符串
		$tmpStr = implode( $tmpArr );
		//通过哈希算法对字符串进行加密
		$tmpStr = sha1( $tmpStr );
		//与加密签名进行对比
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	public function getData(){
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];//接收用户端发送过来的XML数据
		if (!empty($postStr)){
			libxml_disable_entity_loader(true); //XML防止外部实体注入
			//通过simplexml进行xml解析
		    $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		    foreach ($postObj as $key => $value) {
      				$array[$key] = $value;
    			}
    		return $array;
		}	
	}
	private $xmlB = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>";
	private $xmlE 		= "</xml>";
	private $Text 		= "<Content><![CDATA[%s]]></Content>";
	private $Image 		= "<Image>
							<MediaId><![CDATA[%s]]></MediaId>
							</Image>";
	private $Voice 		= "<Voice>
							<MediaId><![CDATA[%s]]></MediaId>
							</Voice>";
	private $Video 		= "<Video>
							<MediaId><![CDATA[%s]]></MediaId>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
							</Video> ";
	private $item		= "<item>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
							<PicUrl><![CDATA[%s]]></PicUrl>
							<Url><![CDATA[%s]]></Url>
							</item>";						
	private $Music 		= "<Music>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
							<MusicUrl><![CDATA[%s]]></MusicUrl>
							<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
							</Music>";

	public function textRes($arr,$content=""){
		$textTpl = $this->xmlB.$this->Text.$this->xmlE;
		if(!empty($content)){
			$resultStr = sprintf($textTpl, $arr['FromUserName'], $arr['ToUserName'], time(), "text", $content);//经过sprintf处理过后，
		}else{
			$resultStr = sprintf($textTpl, $arr['FromUserName'], $arr['ToUserName'], time(), "text", $arr['Content']);
		}
      echo $resultStr;
      exit;
	}
	public function imageRes($arr,$content=""){
		$textTpl = $this->xmlB.$this->Image.$this->xmlE;
		/*$textTpl = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[%s]]></MsgType>
		<Image><MediaId><![CDATA[%s]]></MediaId></Image>
		</xml>";*/
		if(!empty($content)){
			$resultStr = sprintf($textTpl, $arr['FromUserName'], $arr['ToUserName'], time(), "image", $content);
		}else{
			$resultStr = sprintf($textTpl, $arr['FromUserName'], $arr['ToUserName'], time(), "image", $arr['MediaId']);
			//$resultStr = $this->textRes($arr,$arr['MsgType']);
		}
      echo $resultStr;
      exit;
	}
	public function voiceRes($arr,$content=""){
		$textTpl = $this->xmlB.$this->Voice.$this->xmlE;
		if(!empty($content)){
			$resultStr = sprintf($textTpl, $arr['FromUserName'], $arr['ToUserName'], time(), "voice", $content);//经过sprintf处理过后，
		}else{
			$resultStr = sprintf($textTpl, $arr['FromUserName'], $arr['ToUserName'], time(), "voice", $arr['MediaId']);
		}
      echo $resultStr;
      exit;
	}
	public function musicRes($arr,$content=array()){
		$textTpl = $this->xmlB.$this->Music.$this->xmlE;
		if(!empty($content)){
			$resultStr = sprintf($textTpl, $arr['FromUserName'], $arr['ToUserName'], time(), "music", $content['Title'], $content['Description'], $content['MusicUrl'], $content['HQMusicUrl']);
		}else{
			$resultStr = $this->textRes($arr,"抱歉，我们没资源！！");
		}
      echo $resultStr;
      exit;
	}
	public function videoRes($arr,$content=""){
		$textTpl = $this->xmlB.$this->Video.$this->xmlE;
		if(!empty($content)){
			$resultStr = sprintf($textTpl, $arr['FromUserName'], $arr['ToUserName'], time(), "video", $arr['MediaId'],$arr['Title'],$arr['Description']);
		}else{
			$resultStr = sprintf($textTpl, $arr['FromUserName'], $arr['ToUserName'], time(), "video", $arr['MediaId'],"123","456");
		}
      echo $resultStr;
      exit;
	}
	public function newsRes($arr,$content=array(),$num=1){
		if($num>10)exit("图文条数过多,不支持");
		$res = "";
		foreach ($content as $k => $v) {
			$res .= sprintf($this->item, $v['title'],$v['desc'],$v["picUrl"],$v['Url']);
		}
		$itemTpl = "<ArticleCount>$num</ArticleCount><Articles>".$res."</articles>";
		$newsTpl = $this->xmlB."%s".$this->xmlE;
		if(!empty($content)){
			$resultStr = sprintf($newsTpl, $arr['FromUserName'], $arr['ToUserName'], time(), "news", $itemTpl);
		}else{
			$resultStr = $this->textRes($arr,"抱歉，没资源！！");
		}
      echo $resultStr;
      exit;
		

	}
	
}