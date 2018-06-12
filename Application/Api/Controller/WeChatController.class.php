<?php
namespace Api\Controller;
use Think\Controller;
/**
 * 个人毕设——微信端
 */
class WeChatController extends Controller {
 	/*
     *微信消息接收接口(参数echostr只有第一次验证才会传输)
     */
    public function recv(){
    	//1. 获得参数
    	$echostr = $_GET['echostr'];
    	$signature = $_GET['signature'];
    	$timestamp = $_GET['timestamp'];
    	$nonce = $_GET['nonce'];
    	$token = 'weixin';
    	//2. 形成数组，按字典序排序
    	$tmpArr = array($token, $timestamp, $nonce);
    	sort($tmpArr, SORT_STRING);  // use SORT_STRING rule
    	//3. 拼接字符串及加密
    	$tmpStr = implode( $tmpArr );
    	$tmpStr = sha1( $tmpStr );  
    	//4. 比对，返回结果 
    	if( $tmpStr == $signature && $echostr){
    		//第一次验证
    		echo $echostr;
    		die;
    	}else{
    		//非第一次，实现其他功能
    		$this -> responseMsg();
    	}
    }   
    /*
     * 全局access_token获取(已实现)
     */
    private function get_access_token(){
    	//开发者的appid和appsecret
    	//$appid = 'wx88ad600d191b4a14';	//测试id
    	//$appsecret = '09b86d9601cb16174f71f8dd02ab2c08';	//测试secret
    	$appid = 'wx11d098e4ad5c15dd';	//个人id
    	$appsecret = '4e7e467b09f93a92db6c7cb9fc2fe30f';	//个人secert
    	//access_token获取地址：
    	$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
    	$curl = new PublicController();
    	$res = $curl -> curl($url);
    	
    	$arr = json_decode($res,true);		//得到的access_token
		$access_token = $arr['access_token'];
    	S('access_token',$access_token,7200);	//access_token存入缓存

    	$ip_address = $this -> getWeChatadd();	//获取微信服务器地址
    	//var_dump($ip_address);die;	//打印地址——测试使用
    	return $access_token;
    }
    /*
     * 微信服务器地址获取(已实现)
     */
    private function getWeChatadd(){
    	$access_token = S('access_token');
    	
    	//微信服务器IP地址
    	$url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$access_token;
		//curl操作
		$curl = new PublicController();
		$ip_address = $curl -> curl($url);
		$ip_address = json_decode($ip_address,true);
		return $ip_address;
    }
    /* 
     * JSapi_ticket获取
     */
    private function get_jsapi_ticket(){
    	$access_token = S('access_token');
    	if($access_token){
    		;
    	}
    	else{	//过期,重新缓存
    		$access_token = $this -> get_access_token();
    	}
    	//根据access_token来获取jsapi_ticket,并存为缓存(7200秒)
    	$curl = new PublicController();
    	$jsapi_url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
    	$jsapi_json = $curl -> curl($jsapi_url);
    	//将结果转为数组
    	$jsapi_arr = json_decode($jsapi_json,true);
    	//获得ticket
    	$jsapi_ticket = $jsapi_arr['ticket'];
    	//存入缓存
    	S('jsapi_ticket',$jsapi_ticket,7200);
    	return $jsapi_ticket;
    }
    /*
     * 获取media_id
     */
    private function get_media_id(){
    	$access_token = S('access_token');
    	if($access_token){
    		;
    	}
    	else{	//过期,重新缓存
    		$access_token = $this -> get_access_token();
    	}
    	$url = 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token='.$access_token;
    	$arr = array(
    		
    	);
    }
    /*
	 * 创建自定义菜单(创建完成后手机检测)(已实现)
	 * 自定义菜单最多包括3个一级菜单，每个一级菜单最多包含5个二级菜单。
	 * 一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替。
	 *
	 *
	 */
	public function define_menu(){
		header("Content-type:text/html;charset=utf-8");
		//POST示例: https://api.weixin.qq.com/cgi-bin/menu/create?access_token=ACCESS_TOKEN
		$time = time();
		//获取全局access_token
		$access_token = S('access_token');
    	if($access_token){
    		;
    	}
    	else{	//过期,重新缓存
    		$access_token = $this -> get_access_token();
    	}
		
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
		$req = array(
			'button'=>array(
					array(		//第一个一级菜单
						"type"=>"click",
						"name"=>urlencode("111"),
						"key"=>"first",
					),			
					array(		//第二个一级菜单
						"type"=>"click",
						"name"=>urlencode("222"),
						"key"=>"second",
					),			
					array(		//第三个一级菜单(含二级菜单)
						"name"=>urlencode("生成链接"),
						'sub_button' => array(
							array(		//第三个一级菜单的第一个二级菜单
								"type"=>"view",
								"name"=>urlencode("淘宝网"),
								"url"=>"http://www.taobao.com/"
							),	
							array(		//第三个一级菜单的第一个二级菜单
								"type"=>"view",
								"name"=>urlencode("百度首页"),
								"url"=>"http://www.baidu.com/"
							),	
							array(		//第三个一级菜单的第三个二级菜单
								"type"=>"view",
								"name"=>urlencode("腾讯首页"),
								"url"=>"http://v.qq.com/"
							),
							array(		//第三个一级菜单的第三个二级菜单
								"type"=>"view",
								"name"=>urlencode("问卷调查"),
								"url"=>"http://www.my-web-dream.com/WeChat/cus_form_href"
							),		
						
						),//sub_button
							
					),//一级菜单
			),//button
		);//req
		//解决json中文问题
 		$json_req  = urldecode(json_encode($req));	//将中文字符正常插入到json中
		$curl = new PublicController();
 		$res = $curl -> curl_post($url,$json_req);	//返回json结果
		var_dump($res);
	}
    /*
     * 接收事件推送并回复(接受来源为微信服务器，回复给微信用户)
     */
	private function responseMsg(){
		//1.获取微信推送的post数据(XML格式)
		$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
		//2.处理消息类型，并设置回复类型和内容		
		$postObj = simplexml_load_string( $postArr ); //XML数据转对象
		//************接收消息类型分为：事件(event),纯文本(text),图片(image),音频(voice),*********
		//3.接收消息类型为event(事件)
		if(strtolower($postObj -> MsgType) == "event"){
			//事件类型为：subscribe(关注事件)
			if(strtolower($postObj -> Event) == "subscribe"){
				/****************用户上网放行**************/
				$flag = 1;
				S("weixin_id",$flag,30);

				/******************************************/
				//回复用户文本消息
				$toUser = $postObj -> FromUserName;
				$fromUser = $postObj -> ToUserName;
				$time = time();
				$Msgtype = 'text';
				$content = "欢迎关注本商场的微信公众号，请问有什么可以帮助您的呢？";
				$template = ' <xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>';
				$info = sprintf($template,$toUser,$fromUser,$time,$Msgtype,$content);
				echo $info;
			}	//关注事件结束
			//事件类型为：unsubscribe(取消关注事件)
			else if(strtolower($postObj -> Event) == "unsubscribe"){
				//回复用户文本消息
				$toUser = $postObj -> FromUserName;
				$fromUser = $postObj -> ToUserName;
				$time = time();
				$Msgtype = 'text';
				$content = "感谢您的支持，欢迎再次关注";
				$template = ' <xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>';
				$info = sprintf($template,$toUser,$fromUser,$time,$Msgtype,$content);
				echo $info;
			}//取消关注事件结束
			//事件类型为：location(获取用户地理位置)
			else if(strtolower($postObj -> Event) == "location"){
				//位置信息写入数据库
 				$position_json = json_encode($postObj);
				$position = json_decode($position_json,true);
 				$data = array();				
 				$data['openid'] = $position['FromUserName'];//$postObj -> FromUserName;
 				$data['latitude'] = $position['Latitude']; //$data->Latitude;
				$data['longitude'] = $position['Longitude'];//$postObj -> Longitude;
				$data['precision'] = $position['Precision'];//$postObj -> Precision;
				$data['create_time'] = $position['CreateTime'];//$postObj -> CreateTime;
				M('Wechat_position')->add($data);
				//回复用户文本消息
				$toUser = $postObj -> FromUserName;
				$fromUser = $postObj -> ToUserName;
				$time = time();
				$Msgtype = 'text';
				$content = $position_json;	//返回用户JSON数据
				$template = ' <xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>';
				$info = sprintf($template,$toUser,$fromUser,$time,$Msgtype,$content);
				echo $info;
			}
			/****自定义菜单推送消息部分***/
			//事件类型为：click(自定义菜单按钮点击事件)
			else if(strtolower($postObj -> Event) == "click"){
				//点击的first这个按钮
				if(strtolower($postObj -> EventKey) == "first" ){	//EventKey为自定义菜单点击事件的key
					//回复文本消息
					$toUser = $postObj -> FromUserName;
					$fromUser = $postObj -> ToUserName;
					$time = time();
					$Msgtype = 'text';
					$content = '此处返回key为word的文字消息';
					$template = ' <xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>';
					$info = sprintf($template,$toUser,$fromUser,$time,$Msgtype,$content);
					echo $info;
					
				}//第一个点击事件判断结束
				else if(strtolower($postObj -> EventKey) == "second"){	//EventKey为自定义菜单点击事件的key
					//回复多图文消息
					$arr = array(
							array('title' => '百度首页',
									'description' => '"中国第一搜索引擎"',
									'picurl' => 'http://www.my-web-dream.com/Public/img/baidu.jpg',
									'url' => 'http://www.baidu.com'
							),
							//多图文展示(success)
							 array('title' => '腾讯视频',
							 		'description' => '最火爆的电影网站',
							 		'picurl' => 'http://www.my-web-dream.com/Public/img/tencent.jpg',
							 		'url' => 'http://v.qq.com'
							 ),
							array('title' => '淘宝网',
									'description' => '淘宝网，给你不一样的选择',
									'picurl' => 'http://www.my-web-dream.com/Public/img/taobao.jpg',
									'url' => 'http://www.taobao.com'
							),
					);
					//图文变量拼接，其中foreach部分可做成多图文
					$template = '<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<ArticleCount>'.count($arr).'</ArticleCount>
							<Articles> ';
					foreach($arr as $key=>$val){
						$template.=	'<item>
							<Title><![CDATA['.$val['title'].']]></Title>
							<Description><![CDATA['.$val['description'].']]></Description>
							<PicUrl><![CDATA['.$val['picurl'].']]></PicUrl>
							<Url><![CDATA['.$val['url'].']]></Url>
							</item>';
					}
					$template.=	'</Articles>
							</xml>';
					$toUser = $postObj -> FromUserName;
					$fromUser = $postObj -> ToUserName;
					$time = time();
					$Msgtype = 'news';
					$info = sprintf ($template,$toUser,$fromUser,$time,$Msgtype);
					echo $info;die;
				
				}	//第二个点击事件判断结束
				
			}//click事件判断结束
			//事件类型为：view(自定义菜单点击跳转事件),网页直接跳转，不会进行推送
			else if(strtolower($postObj -> Event) == "view"){
				//回复文字消息
				/*
				 * 
				 * 会直接跳转，No回复
				 * 
				 * 
				 */
			}//view事件判断结束
			/****自定义菜单推送消息部分***/
		}//event事件结束（关注+取消关注+获取位置+自定义菜单）
		//接收消息类型，判断是否为普通消息推送
		else{	
			//普通消息类型1：纯文本消息(接收文本回复文本消息)(已实现)
			if(strtolower($postObj -> MsgType) == "text" && strtolower($postObj -> Content) != "tuwen"){
				//可以再分类型（区分用户发送的纯文本消息，根据不同消息回复不同信息）
				switch(trim($postObj -> Content)){
					case '商场':
						$res = "官方电话：18602613056";
						break;
					case '地址': //纯文本消息设置链接
						$res = "成都市双流区万达广场"; 
						break;
					default:
						$res = "感谢您的询问，稍后为您解答";
						break;
					
				}
				//回复文本消息
				$toUser = $postObj -> FromUserName;
				$fromUser = $postObj -> ToUserName;
				$time = time();
				$Msgtype = 'text';
				$content = $res;
				$template = ' <xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>
								<Content><![CDATA[%s]]></Content>
								</xml>';
				$info = sprintf($template,$toUser,$fromUser,$time,$Msgtype,$content);
				echo $info;
				die;
			}//纯文本结束
			//普通消息类型2：图片消息(接收图片回复文本消息)
			 else if(strtolower($postObj -> MsgType) == "image"){
			 	//回复文本消息
				$toUser = $postObj -> FromUserName;
				$fromUser = $postObj -> ToUserName;
				$time = time();
				$Msgtype = 'text';
				$content = "感谢您的图片，稍后为您解答";
				$template = ' <xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>
								<Content><![CDATA[%s]]></Content>
								</xml>';
				$info = sprintf($template,$toUser,$fromUser,$time,$Msgtype,$content);
				echo $info;
			}//图片结束
			//普通消息类型3：图文消息(接收文本回复图文消息)
			else if(strtolower($postObj -> MsgType) == "text" && strtolower($postObj -> Content) == "tuwen"){
				//回复多图文消息
				$arr = array(
					array('title' => '数据监测系统',
						'description' => '超一流的系统',
						'picurl' => 'http://www.my-web-dream.com/Public/img/login/logo.jpg',
						'url' => 'http://www.my-web-dream.com/login/login.html'			
					),
					//多图文展示(success)
					/*
					array('title' => '淘宝网',
							'description' => '女生最爱的网站',
							'picurl' => 'http://121.40.92.106/Public/img/success.png',
							'url' => 'http://www.taobao.com'
					),
					array('title' => '腾讯视频',
							'description' => '国内一流视频网站',
							'picurl' => 'http://121.40.92.106/Public/img/error.png',
							'url' => 'http://v.qq.com'
					),*/
				);
				//图文变量拼接，其中foreach部分可做成多图文
				$template = '<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<ArticleCount>'.count($arr).'</ArticleCount>
							<Articles> ';
				foreach($arr as $key=>$val){
					$template.=	'<item>
							<Title><![CDATA['.$val['title'].']]></Title>
							<Description><![CDATA['.$val['description'].']]></Description>
							<PicUrl><![CDATA['.$val['picurl'].']]></PicUrl>
							<Url><![CDATA['.$val['url'].']]></Url>
							</item>';
				}
				$template.=	'</Articles>
							</xml>';
				$toUser = $postObj -> FromUserName;
				$fromUser = $postObj -> ToUserName;
				$time = time();
				$Msgtype = 'news';
				$info = sprintf ($template,$toUser,$fromUser,$time,$Msgtype);
				echo $info;die;
			}	//图文结束(注意，foreach子元素个数不能超过10个)
			
			
		}	//普通消息类型结束
	}	//当前方法结束
	
	/*
	 *  临时二维码接口(已实现)
	 */
	Public function QR_code_time(){
		header("Content-type:text/html;charset=utf-8");
		//获取全局access_token
		$access_token = S('access_token');
    	if($access_token){
    		;
    	}
    	else{	//过期,重新缓存
    		$access_token = $this -> get_access_token();
    	}
		
		//1.获取临时二维码 Ticket票据
		$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
		$arr = array(
				'expire_seconds' => 604800,
				'action_name' => 'QR_SCENE',
				'action_info' => array(
					'scene' => array(
						'scene_id' => '2016'
					)
				)
			);
		$json_arr = json_encode($arr);
		//curl获取结果
		$curl = new PublicController();
		$curl_info = $curl -> curl_post($url,$json_arr);
		$curl_info = json_decode($curl_info,true);
		$ticket = $curl_info['ticket'];	//得到ticket
		$QR_code_url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
		echo "临时二维码<hr/>";
		echo "<img src='".$QR_code_url."'/>";	//临时二维码生成成功！
	}
	/*
	 *	永久二维码接口(已实现)
	 */
	Public function QR_code_forever(){
		header("Content-type:text/html;charset=utf-8");
	//获取全局access_token
		$access_token = S('access_token');
    	if($access_token){
    		;
    	}
    	else{	//过期,重新缓存
    		$access_token = $this -> get_access_token();
    	}
	
		//1.获取永久二维码 Ticket票据
		$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
		$arr = array(
				'action_name' => 'QR_LIMIT_SCENE',
				'action_info' => array(
						'scene' => array(
								'scene_id' => '2016'
						)
				)
		);
		$json_arr = json_encode($arr);
		//curl获取结果
		$curl = new PublicController();
		$curl_info = $curl -> curl_post($url,$json_arr);
		$curl_info = json_decode($curl_info,true);
		$ticket = $curl_info['ticket'];	//得到ticket
		$QR_code_url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
		echo "永久二维码<hr/>";
		echo "<img src='".$QR_code_url."'/>";	//永久二维码生成成功！
	}
	
	
	/***********网页授权验证请通过二维码生成器，通过链接地址生成二维码，微信扫一扫进入该页面*******/
	/*
	 * 网页授权接口(基本信息接口)(已实现)
	 * 此页面用于自定义菜单的路径，但是实际页面写在get_userbasic中，此页面仅为跳转页面
	 */
	public function app_web_basic(){
		
		//1.获取code	
		$scope = 'snsapi_base';	//授权作用域(snsapi_base或snsapi_userinfo)
		//开发者的appid
		$appid = 'wx88ad600d191b4a14';	//测试id
		//$appid = 'wx11d098e4ad5c15dd';	//个人id
		//回调code的地址
		$redirect_uri = urlencode('http://www.my-web-dream.com/WeChat/get_userbasic');	//回调地址,进行url编码(code参数会发放到此页面)
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope='.$scope.'&state=123#wechat_redirect';
		//自动跳转至 redirect_uri/?code=CODE&state=STATE。
		header('location:'.$url);
	}
	//code的回调地址
	public function get_userbasic(){
		//获取到的code，用来获取网页授权access_token和用户的openid
		$code = $_GET['code'];	
		//开发者的appid和appsecret
		$appid = 'wx88ad600d191b4a14';	//测试id
		$appsecret = '09b86d9601cb16174f71f8dd02ab2c08';	//测试secret
		//$appid = 'wx11d098e4ad5c15dd';	//个人id
		//$appsecret = '4e7e467b09f93a92db6c7cb9fc2fe30f';	//个人secerti
		//2.获取网页授权access_token及openid(用户基本信息)
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
		$curl = new PublicController();
		$res = $curl -> curl($url);	
		//已获得网页access_token，用户openid等基本信息
		$res = json_decode($res,true);
		var_dump($res);  
		//$this -> display();写成页面，该页面以获得用户基本信息
	}
	
	/*
	 * 网页授权接口(详细信息接口,建议使用此接口) (已实现)
	 * 详细信息接口会自动弹出用户授权提示页面
	 */
	public function app_web_detail(){
		//1.获取code	
		$scope = 'snsapi_userinfo';	//授权作用域(snsapi_base或snsapi_userinfo)
		//开发者的appid
		$appid = 'wx88ad600d191b4a14';	//测试id
		//$appid = 'wx11d098e4ad5c15dd';	//个人id
		//回调code的地址
		$redirect_uri = urlencode('http://www.my-web-dream.com/WeChat/get_userinfo');	//回调地址,进行url编码(code参数会发放到此页面)
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope='.$scope.'&state=123#wechat_redirect';
		//测试：
		header('location:'.$url);
	}
	//code的回调地址
	public function get_userinfo(){
		//code已经获得
		$code = $_GET['code'];	//获取到的code，用来换去access_token
		//开发者的appid和appsecret
		$appid = 'wx88ad600d191b4a14';	//测试id
		$appsecret = '09b86d9601cb16174f71f8dd02ab2c08';	//测试secret
		//$appid = 'wx11d098e4ad5c15dd';	//个人id
		//$appsecret = '4e7e467b09f93a92db6c7cb9fc2fe30f';	//个人secert
		//2.获取网页授权access_token及openid(用户基本信息)
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
		$curl = new PublicController();
		$res = $curl -> curl($url);	
		$res = json_decode($res,true);
		//此时已获得网页授权access_token以及用户的openid等基本信息
		$access_token = $res['access_token'];
		$openid = $res['openid'];
		//3.获取用户详细信息，包含昵称，头像，地理位置等
		$url_info = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN ';
		$res_info = $curl -> curl($url_info);	//获得用户详细信息
		var_dump($res_info);
	}
	
	/*
	 * JS-SDK分享功能(草料二维码生成二维码图片)
	 */
	public function share_show(){	
		/*****获取JSapi_ticket*****/
		//判断缓存是否存在
		$jsapi_ticket = S('jsapi_ticket');
		if($jsapi_ticket){		//JSapi存在
			;
		}
		else{
			//JSspi_ticket不存在则重新获取
			$jsapi_ticket = $this -> get_jsapi_ticket();
		}
		/*****JSapi_ticket获取成功*****/
		$timestamp = time();
		//获取随机串
		$nonceStr = $this -> get_randcode();
		//设置URL
		$url = 'http://www.my-web-dream.com/WeChat/share';
		//$url = 'http://mp.weixin.qq.com';
		//获取signature
		$signature_url = 'jsapi_ticket='.$jsapi_ticket.'&noncestr='.$nonceStr.'&timestamp='.$timestamp.'&url='.$url;
		//进行sha1加密，获得最终的signature
		$signature = sha1($signature_url);
		$this ->assign('timestamp',$timestamp);
		$this ->assign('nonceStr',$nonceStr);
		$this ->assign('signature',$signature);	//就你最麻烦！！
		$this ->assign('jsapi_ticket',$jsapi_ticket);
		$this ->display();	
	}
	/*
	 * 获取16位随机码
	 */
	private function get_randcode($num='16'){
		$arr = array(
			'A','B','C','D','E','F','G','H','I','J','K','L','M',
			'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
			'a','b','c','d','e','f','g','h','i','j','k','l','m',
			'n','o','p','q','r','s','t','u','v','w','x','y','z',
			'0','1','2','3','4','5','6','7','8','9'
		);
		$tmpstr = '';
		$max = count($arr);
		for($i=0;$i<$num;$i++){	//生成16位随机串
			 //随机数
			 $key = rand(0,$max-1); //'A' -> array['0'];'9' => array[$max-1]
			 $tmpstr .= $arr[$key];
		}
		return $tmpstr;
	}
	/*
	 * 高级群发接口(测试账号下，预览接口,测试文本消息)  URL调用此函数测试，ok!
	 */
	public function sendAll(){
		$access_token = S('access_token');
    	if($access_token){
    		;
    	}
    	else{	//过期,重新缓存
    		$access_token = $this -> get_access_token();
    	}
		//2.组装群发数据 array
		$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$access_token;
		//3.array->json
		//单文本消息推送 ok
/*		$arr = array(
					"touser" => "oHev1v_sMNwhQzMtjAvSWkx5YPEA",	//openID
					"text" => array(
							"content" => "I am a girl",		//文本内容
					),
					"msgtype" => "text",
		);*/
		//单图文消息推送
		$arr = array(
				"touser" => "oHev1v3TFYlRK6krLAo_6g4lJ0P0",
				"mpnews" => array(
						"media_id" => "123dsdajkasd231jhksad",//测试使用，需获取正式media_id
				),
				"msgtype" => "mpnews",
		);
		$post_data = json_encode($arr);
		//4.调用curl
		$curl = new PublicController();
		//触发，手机端查看结果
		$curl -> curl_post($url, $post_data);
	}


/******************************自己编写的页面*************************************/
/**
* 获取用户详细信息，填写表单
*/
public function cus_form_href(){
	//1.获取code	
	$scope = 'snsapi_userinfo';	//授权作用域(snsapi_base或snsapi_userinfo)
	//开发者的appid
	$appid = 'wx88ad600d191b4a14';	//测试id
	//$appid = 'wx11d098e4ad5c15dd';	//个人id
	//回调code的地址
	$redirect_uri = urlencode('http://www.my-web-dream.com/WeChat/cus_estimate');	//回调地址,进行url编码(code参数会发放到此页面)
	$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope='.$scope.'&state=123#wechat_redirect';
	//测试：
	header('location:'.$url);
}
/**
* 实际页面
*/
public function cus_estimate(){
	//code已经获得
	$code = $_GET['code'];	//获取到的code，用来换去access_token
	//开发者的appid和appsecret
	$appid = 'wx88ad600d191b4a14';	//测试id
	$appsecret = '09b86d9601cb16174f71f8dd02ab2c08';	//测试secret
	//$appid = 'wx11d098e4ad5c15dd';	//个人id
	//$appsecret = '4e7e467b09f93a92db6c7cb9fc2fe30f';	//个人secert
	//2.获取网页授权access_token及openid(用户基本信息)
	$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
	$curl = new PublicController();
	$res = $curl -> curl($url);	
	$res = json_decode($res,true);
	//此时已获得网页授权access_token以及用户的openid等基本信息
	$access_token = $res['access_token'];
	$openid = $res['openid'];
	//3.获取用户详细信息，包含昵称，头像，地理位置等
	$url_info = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN ';
	$res_info = $curl -> curl($url_info);	//获得用户详细信息
	var_dump($res_info);
	$this -> display();
}














}