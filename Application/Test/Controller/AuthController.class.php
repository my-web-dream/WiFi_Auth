<?php
namespace Test\Controller;
use Think\Controller;


header("Content-type:text/html;charset=utf-8");

/**
 * 该类为Token验证类，用于网关向认证服务器验证用户的token是否有效
 * 路径：http://localhost/mywifi/api.php/auth/?stage=counters&ip=7.0.0.107&mac=00:40:05:5F:44:43&token=4f473ae3ddc5c1c2165f7a0973c57a98&incoming=6031353&outgoing=827770
 */
class AuthController extends Controller{
	
	
    public function index(){
       
        $data['gw_id'] = I('get.gw_id');
        $data['stage'] = I('get.stage');    //第一次验证stage = login，其他情况stage = counters
    	$data['ip'] = I('get.ip');
    	$data['mac'] = I('get.mac');
    	$data['token'] = I('get.token'); //没有接收到，无法进行验证，放行
    	$data['incoming'] = I('get.incoming');
    	$data['outgoing'] = I('get.outgoing');
        $data['mcode'] = I('get.mcode');
        $data['time'] = time();
        $data['date'] = date("Y-m-d H:i:s",time());
    	//echo $ip.'__'.$mac.'__'.$token.'__'.$incoming.'__'.$outgoing;
        M('Ap_auth') ->add($data);

        $get = I("get.");
        foreach($get as $key=>$val){
            $test['content'] .= $key.'=>'.$val.' ';
        }
        M('Test') -> add($test);

        //对wifidog上传的token经行验证，如果正确返回1，否则返回-1
        //if($data['stage'] == 'login'){      //用户状态为登录，验证token，其他状态无需进行验证
            //if($data['token'] == cookie("token")){
            echo "Auth: 1";     //授权通过
            //}else{
            //    echo '-1';  //授权未通过
            //}
            //http://192.168.1.1:2060/wifidog/auth?token=Njg6Zjc6Mjg6ZDM6OTk6MmE=
        //} 
       
    }
   
    
    
    
    
    
    
    
    
    
    
    
    
   
}
