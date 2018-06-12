<?php
namespace Admin\Model;
use Think\Model;

/**
 * 管理员信息
 */
class AdminModel extends Model {
	
	
	/**
	 * 查询往日活动信息，默认今日
	 */
	public function active($date){	//页数，日期，编号，类型
	
		$date = strtotime($date);	//获取前台提交时间
		//获取当日起止时间戳
		$start = mktime(0,0,0,date("m",$date),date("d", $date),date("Y", $date));
		$end = mktime(23,59,59,date("m",$date),date("d", $date),date("Y", $date));
		//查询条件
		$res['bactv.create_time'] = array('BETWEEN',array($start,$end));
		//查询当日总的数据数量
		$info = M('Busin_active') ->alias('bactv')
		->join("join __BUSIN_INFO__ binfo on binfo.id = bactv.busin_id")
		->join("join __BUSIN_TYPE__ btype on btype.id = binfo.type_id")
		-> where($res)
		-> field('binfo.name,binfo.floor,btype.type_name,bactv.create_time,bactv.content')
		-> order("binfo.floor asc")
		-> select();
		return $info;
	
	}
	
	/**
	 * 查询路由器状态信息，默认今日
	 */
	public function router_info($date){	//页数，日期，编号，类型
	
		$date = strtotime($date);	//获取前台提交时间
		//获取当日起止时间戳
		$start = mktime(0,0,0,date("m",$date),date("d", $date),date("Y", $date));
		$end = mktime(23,59,59,date("m",$date),date("d", $date),date("Y", $date));
		//查询条件
		$res['ap_sta.time'] = array('BETWEEN',array($start,$end));
		//查询当日总的数据数量
		$info = M('Ap_status') ->alias('ap_sta')
		-> where($res)
		-> field('ap_sta.id,ap_sta.sys_memfree,ap_sta.sys_load,ap_sta.date')
		
		-> select();
		return $info;
	
	}
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * 设备信息分页显示
	 */
	public function page($page){	//页数，日期，编号，类型
		$page_size = 10;	//每页10条数据
		$start_data = ($page-1) * $page_size;	//设定数据起始位置
		//查询设备总数量
		$info = M('Device_handlering')
		-> field('id')
		-> select();
		$total = count($info);	//总条数
		//查询当前页符合条件数量
		$select_info = M('Device_handlering')
		-> field('id,deviceID,IMEI,IMSI,create_time,is_bind')
		-> order('create_time desc')
		-> limit($start_data,$page_size)
		-> select();
		//输出总页数
		$total_pages = ceil($total/$page_size);	//ceil,floor
		return array('page'=>$page,
				'total_pages'=>$total_pages,
				'select_info'=>$select_info,
		);
	}
	
}
