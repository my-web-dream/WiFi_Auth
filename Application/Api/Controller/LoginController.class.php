<?php
namespace Api\Controller;
use Think\Controller;

header("Content-type:text/html;charset=utf-8");

/** 
	* wifidog登录类，用于显示认证页面以及收集用户的登录信息
	* 路径格式：http://localhost/mywifi/api.php/login/?gw_address=192.168.1.1&gw_port=2600&gw_id=ABCDEFG&url=www.baidu.com&mac=C4:8E:8F:73:F4:63
	* 注意，接受信息必须写在index方法中
	*/
class LoginController extends Controller{
	

    public function index(){
		$data['gw_address'] = I('get.gw_address');
    	$data['gw_id'] = I('get.gw_id');
    	$data['gw_port'] = I('get.gw_port');
        $data['ip'] = I('get.ip');
        $data['mac'] = I('get.mac');
        $data['key'] = I('get.key');
        $data['wwver'] = I('get.wwver');
        $data['onlineuser'] = I('get.onlineuser');
        $data['url'] = I('get.url');
        $data['time'] = time();
        $data['date'] = date("Y-m-d H:i:s",time());

    	if(!$data['gw_id']){
    		;
    	}else{
    		 M('Ap_login') -> add($data);
    	}
        //$token = mac_encode($data['mac']);
        $token = md5($data['mac']);
        //将用户的mac地址保存，通过md5生成唯一的token,供wifidog进行验证
        if(!cookie("token")){
            cookie("token",$token,60);
        }
        //显示用户认证页面
        $this -> display();
    }
	//免费上网接口,无需服务器进行验证
    public function free(){
        //向wifidog返回token信息，便于后期wifidog进行验证
        if(!cookie("token")){  
            //token不存在，重新跳转到认证页面
            $this->error();
        }else{          
            //token存在，向wifidog返回token，用于wifidog后面向服务器的auth方法验证token
            header("location: http://192.168.11.1:2060/wifidog/auth?token=".cookie("token"));  
        }
    }
    //身份证认证，需服务器进行验证
    public function id_card(){

    	$id_card = I("post.id_card");
    	if(!$id_card){
    		$this -> display();
    	}else{
    		//查找用户是否存在
    		$id = M('User_info') 
    		-> where(array('ID_card'=>$id_card))
    		-> getField("id");
    		if($id){	//用户id存在，放行
    			header("location: http://192.168.11.1:2060/wifidog/auth?token=".cookie("token"));
    		}else{
    			//用户信息不存在
    			$this->error();
    		}
    	}
    }
    //微信认证，显示页面
    public function weixin(){
    	$this -> display();	
    }
    //微信认证，检查公众号是否关注
    public function weixin_check(){
        $flag = S("weixin_id");
        if(!$flag){
            $flag = 0;
        }
    	echo $flag;
    }
    //微信认证，成功跳转
    public function weixin_sub(){
    	header("location: http://192.168.11.1:2060/wifidog/auth?token=".cookie("token"));
    }
    
   //  http://localhost/mywifi/api.php/login/test/?gw_address=192.168.1.1&gw_port=2600&gw_id=ABCDEFG&url=www.baidu.com
    public function test(){
        //echo base64_encode("74:a5:28:75:28:6e");die;
        echo "*****************************************加密********************************************";
        echo "<br>";
        $str = "74:a5:28:75:28:6e";
        echo '用户mac地址($str1):  '.$str."<br><br>";
        $num = 24;
        $secret_key = $this -> get_randcode($num);  //24位随机字符串
        echo '本次随机秘钥($str2):  '.$secret_key."<br><br>";
        $strArr = str_split(base64_encode($str));    //24位二维数组
//        echo 'Base64初次编码($arr1):  '."<br>";
//        var_dump($strArr);
        $secret_key_Arr = str_split($secret_key);
        foreach ($secret_key_Arr as $key => $value){
            $strArr[$key].=$value;
        }
//        echo '数组拼接($arr):  '."<br>";
//        var_dump($strArr);
        $encode_str =  implode('', $strArr);
        echo '加密结果($token):  '.$encode_str;
        echo "<br><br>";
        echo "*****************************************解密*********************************************";
        echo "<br><br>";
        echo '待解密结果($token):  '.$encode_str."<br><br>";
        $strArr2 = str_split($encode_str,2);
//        echo '待拆分数组($arr):  '."<br>";
//       var_dump($strArr2);
        $secret_key_Arr2 = str_split($secret_key);
        foreach ($secret_key_Arr2 as $key => $value){
            $strArr2[$key] = $strArr2[$key][0];
        }
//        echo '数组已拆分($arr1):  '."<br>";
//        var_dump($strArr2);
        $decode_str = base64_decode(implode("",$strArr2));
        echo '恢复原始mac地址($str1):  '.$decode_str;
    }
      /*
     * 获取24位随机码
     */
    public function get_randcode($num='24'){
        $arr = array(
            'A','B','C','D','E','F','G','H','I','J','K','L','M',
            'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
            'a','b','c','d','e','f','g','h','i','j','k','l','m',
            'n','o','p','q','r','s','t','u','v','w','x','y','z',
            '0','1','2','3','4','5','6','7','8','9'
        );
        $tmpstr = '';
        $max = count($arr);
        for($i=0;$i<$num;$i++){ //生成24位随机串
             //随机数
             $key = rand(0,$max-1); //'A' -> array['0'];'9' => array[$max-1]
             $tmpstr .= $arr[$key];
        }
        return $tmpstr;
    }
}
