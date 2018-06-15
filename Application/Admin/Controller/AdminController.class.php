<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 该类为登录类，用于系统登录页面
 */
header("Content-type:text/html;charset=utf-8");

class AdminController extends Controller{
	
	/**
	 * 禁止非空登录
	 */
    public function __construct(){
    	parent::__construct();	//引入父类的construct方法，防止报错
    	if(!$_SESSION['username']){
    		$this->redirect('Login/login','', 3,'非法登录，请重新登录...');
    	}
    }
	public function test(){
		echo time();
	}
	/**
	 * 管理员主页面
	 */
    public function index(){
    	
    	$info = M('Basic_info') -> find();
    	$this ->assign("info",$info);
        $this -> display();
    }
    /**
     * 商家管理--所有商铺信息
     */
    public function busin(){
    	
    	$info = M('Busin_info') ->alias('binfo')
    	->join(" join __BUSIN_TYPE__ btype on binfo.type_id = btype.id")
    	->order("binfo.type_id asc")
    	-> select();
    	$this -> assign("info",$info);
    	$this -> display();
    }
    /**
     * 商家管理--添加商家信息
     */
    public function busin_add(){
    	$this -> display();
    }
    /**
    * 添加商家成功
    */
    public function busin_add_sub(){
        $data['name'] = I("post.name");
        $data['people'] = I("post.people");
        $data['phone'] = I("post.phone");
        $data['describe'] = I("post.describe");
        $data['type_id'] = 5;//I("post.name");
        $data['floor'] = 3;//("post.name");
        M("Busin_info") ->add($data);
        $this->redirect("Admin/busin");
    }
    /**
     * 活动管理--查看商家活动
     */
    public function active(){
    	$date = I('get.date');//获取前台提交时间
    	$_GET['time'] = default_time($date);//用于前台显示
    	//提交到后台进行查询
    	$info = D("Admin") -> active(default_time($date));
    	$this -> assign("info",$info);
    	$this -> display();
    }
    /**
     * 路由器管理
     */
    public function router(){
    	$date = I('get.date');//获取前台提交时间
    	$_GET['time'] = default_time($date);//用于前台显示
    	//提交到后台进行查询,得到路由器的内存状态以及系统负载
    	$select_info = D("Admin") -> router_info(default_time($date));
    	if(!$select_info){
    		$this -> assign("test","no");
    	}else{
    		$this -> assign("test","yes");
    	}
    	//查询基本信息
    	$basic_info = M("Ap_status")
    	->order("sys_uptime desc")
    	->field("gw_id,sys_uptime,wifidog_uptime")
    	->find();
    	//显示基本信息:id,开机运行时间，wifidog运行时间
    	$this -> assign("router_id", $basic_info['gw_id']);
    	$this -> assign("sys_uptime", $basic_info['sys_uptime']);
    	$this -> assign("wifidog_uptime", $basic_info['wifidog_uptime']);

        //var_dump($select_info);die;
    	//对查询出的二维数组进行整理
    	$info_first = $this -> router_data_choose($select_info); //第一步整理
    	$res = $this -> router_data($info_first); //第二步整理
    	//前台显示信息
    	$this -> assign("date", default_time($date)); //前台显示当前日期
    	$this -> assign("router_time", $res['k1']); //显示时间数据
    	$this -> assign("router_sys_memfree", $res['k2']); //显示内存数据
    	$this -> assign("router_sys_load", $res['k3']); //显示负载数据
    	$this -> display();
    	
    }
    /**
     * 路由器数据整理(第一步)：隔10次取一条数据
     */
    public function router_data_choose($info){
    	$count = 0;
    	foreach ($info as $key => $val){
    		if($key % 2 == 0){
    			$info_first[$count]['id'] =  $val['id'];
    			$info_first[$count]['date'] =  $val['date'];
    			$info_first[$count]['sys_memfree'] =  $val['sys_memfree'];
    			$info_first[$count]['sys_load'] =  $val['sys_load'];
    			$count++;
    		}
    		
    	}
    	return $info_first;
    }
    /**
     * 路由器数据整理(第二步)
     */
    public function router_data($info){
    	
    	$router_time = ''; //初始拼接时间空
    	$router_sys_memfree = ''; //初始拼接速度空
    	$router_sys_load = ''; //初始拼接里程空
    	foreach ($info as $key => $val) {
    		$router_time .= $val['date'] . ','; //将标准格式的时间连接起来
    		$router_sys_memfree .= $val['sys_memfree'] . ',';	//系统内存拼接
    		$router_sys_load .= $val['sys_load'] . ',';	//负载拼接	
    	}
    	$router_time = rtrim($router_time, ',');   //删除最后一个逗号
    	$router_time = str_replace(',', "','", $router_time); //将原" , "改为"  ','  "
    	$router_sys_memfree = rtrim($router_sys_memfree, ',');
    	$router_sys_load = rtrim($router_sys_load, ',');
    	return array('k1'=>$router_time,'k2'=>$router_sys_memfree,'k3'=>$router_sys_load);
    	 
    }
    /**
     * 发布公告
     */
    public function notice_add(){
    	$date = I('get.date');//获取前台提交时间
    	$_GET['time'] = default_time($date);//用于前台显示
    	$this ->display();
    }
    
    /**
     * 查看公告
     */
    public function notice(){
    	$info = M('system_notice') -> select();
    	$this -> assign("info",$info);
    	$this ->display();
    }
    /**
     * 联系我们
     */
    public function connect(){
    	$this -> display();
    }
   
    
    
    
    
    
    
    
    
    
    
    
    
   
}
