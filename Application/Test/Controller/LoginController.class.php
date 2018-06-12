<?php
namespace Test\Controller;
use Think\Controller;

header("Content-type:text/html;charset=utf-8");

/** 
	* wifidog登录类，用于显示认证页面以及收集用户的登录信息
	* 路径格式：http://localhost/mywifi/api.php/login/?gw_address=192.168.1.1&gw_port=2600&gw_id=ABCDEFG&url=www.baidu.com
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
    	//echo $gw_address.'__'.$gw_id.'__'.$gw_port; 
        M('Ap_login') -> add($data);
       
        //对用户的mac地址进行检测，看是否存在
        if($data['mac']){
            //将用户的mac地址保存，通过base64生成唯一的token,供wifidog进行验证，指定时间为60s
            cookie("token",base64_encode($data['mac']),600);
        }else{
            cookie("token","",600);
        }
      
         //显示用户认证页面
        $this -> display();
    }

    public function submit(){
        //首先去服务器核对用户的信息是否正确
       
        //向wifidog返回token信息，便于后期wifidog进行验证
        if(cookie("token") == ''){  
            //token不存在，重新跳转到认证页面
            $this->redirect("test.php/Login/index",'', 3,'token不存在，请重新认证');
        }else{  
             //token存在，向wifidog返回token，用于wifidog后面向服务器的auth方法验证token
            header("location: http://192.168.1.1:2060/wifidog/auth?token=".cookie("token"));  
        }

    }
   //  http://localhost/mywifi/api.php/login/test/?gw_address=192.168.1.1&gw_port=2600&gw_id=ABCDEFG&url=www.baidu.com
   public function test(){
        //header("location: http://www.baidu.com");  die;
        
        $get = I("get.");
        foreach($get as $key=>$val){
            $test .= $key.'=>'.$val.' ';
        }
        
        var_dump($test);
   }

}
