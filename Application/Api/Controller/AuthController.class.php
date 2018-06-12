<?php
namespace Api\Controller;
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
    	$data['token'] = I('get.token'); 
    	$data['incoming'] = I('get.incoming');
    	$data['outgoing'] = I('get.outgoing');
        $data['mcode'] = I('get.mcode');
        $data['time'] = time();
        $data['date'] = date("Y-m-d H:i:s",time());
    	if(!$data['gw_id']){
    		;
    	}else{
    		M('Ap_auth') ->add($data);
    	}

        if($data['stage'] == 'login'){      //用户状态为登录，验证token，其他状态无需进行验证
            //$mac_decode = mac_decode($data['token']);
            if($data['token'] == md5($data['mac'])){
                echo "Auth: 1";     //授权通过
            }else{
               echo "Auth: 0";
            }
           
        }
        else if($data['stage'] == 'counters'){
            //做一个简单的流量判断验证，下载流量超值时，返回下线通知，否则保持在线
            if($data['incoming'] > 10000000)
            {
                echo "Auth: 0"; //通知下线
            }else{
                echo "Auth: 1\n";           
            }
        }
        else{   //其他情况都返回拒绝，包括状态为logout
             echo "Auth: 0"; 
        }

           

    }
    public function test(){
          //测试：
        //$test['content'] = $data['token']."---auth---".cookie("token");
        //M("Test") -> add($test);
    	echo cookie("token");
    }
    
 
    
   
}
