<?php
namespace Api\Controller;
use Think\Controller;


header("Content-type:text/html;charset=utf-8");

/** 
	* wifidog心跳数据类，wifidog在启动后每分钟访问一次此方法
	* 路径格式：http://localhost/mywifi/api.php/ping/?gw_id=ABCDEFG&sys_uptime=742725&sys_memfree=2604&sys_load=0.03&wifidog_uptime=3861
	* 注意，接受信息必须写在index方法中
	*/
class MessageController extends Controller{

    public function index(){
        $data['message'] = I('get.message');
        $data['time'] = time();
        $data['date'] = date("Y-m-d H:i:s",time());
        if(!$data['message']){
        	;
        }
        else{
        	M("Ap_message") -> add($data);
        }    	
    	$this -> display();
    	cookie(NULL);
        session(NULL);
        S(NULL);

    }
   

   
}
