<?php
namespace Test\Controller;
use Think\Controller;


header("Content-type:text/html;charset=utf-8");
/**
 * 该类为认证成功类
 * 路径：http://localhost/mywifi/api.php/portal/?gw_id=ABCDEFG&token=AABBCCDD
 */
class PortalController extends Controller{
	
	//访问路径：http://localhost/mywifi/api.php/Login/index
    public function index(){
    	$data['gw_id'] = I('get.gw_id');
    	$data['token'] = I('get.token');
    	$data['time'] = time();
        $data['date'] = date("Y-m-d H:i:s",time());
    	//echo $gw_id.'__'.$token; 
    	M('Ap_portal') ->add($data);
        $this ->display();
    }
   
    
    
    
    
    
    
    
    
    
    
    
    
   
}
