<?php
namespace Test\Controller;
use Think\Controller;


header("Content-type:text/html;charset=utf-8");

/** 
	* wifidog心跳数据类，wifidog在启动后每分钟访问一次此方法
	* 路径格式：http://localhost/mywifi/api.php/ping/?gw_id=ABCDEFG&sys_uptime=742725&sys_memfree=2604&sys_load=0.03&wifidog_uptime=3861
	* 注意，接受信息必须写在index方法中
	*/
class PingController extends Controller{

    public function index(){
        $data['gw_id'] = I('get.gw_id');
    	$data['sys_uptime'] = I('get.sys_uptime');
    	$data['sys_memfree'] = I('get.sys_memfree');
    	$data['sys_load'] = I('get.sys_load');
    	$data['wifidog_uptime'] = I('get.wifidog_uptime');
        $data['mcode'] = I('get.mcode');
        $data['time'] = time();
        $data['date'] = date("Y-m-d H:i:s",time());
    	//echo $sys_uptime.'__'.$gw_id.'__'.$sys_memfree.'__'.$sys_load .'__'.$wifidog_uptime; 
        M('Ap_status')->add($data);
        echo 'Pong';
    }
   
    
    
    
    
    
    
    
    
    
    
    
    
   
}
