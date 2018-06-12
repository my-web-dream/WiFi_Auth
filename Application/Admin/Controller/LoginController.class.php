<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 该类为登录类，用于系统登录页面
 */
header("Content-type:text/html;charset=utf-8");

class LoginController extends Controller {

    /**
     * 登录界面展示
     */
    public function login() {
        $this->display();
    }

    /**
     * 登陆跳转
     */
    public function login_in() {
        // 获取用户名和密码
        $username = I('post.username');
        $password = I('post.password');
        $res = '';
        $info = M('Staff_info')
        	->where(array('username' => $username,'status'=>1))
        	->find();
        if(!$info){	//用户不存在
        	$this->error('用户不存在');
        }else{	//用户名正确
        	if(md5($password) == $info['password']){ //用户名正确+密码正确
        		session('username', $username);	//用户名
        		session('auth', $info['auth']);	//权限
        		session('auth_id', $info['auth_id']);	//权限id
        		if($info['auth'] == 'admin'){
        			session('auth_name', '平台管理员');	//权限
        			$this->redirect('Admin/index','', 3,'页面跳转中...');
        		}else if($info['auth'] == 'busin'){
        			session('auth_name','普通商家');
        			$this->redirect('Busin/index','', 3,'页面跳转中...');
        		}else{ //用户名正确+密码正确，实际报错
        			$this->error('权限错误');
        		}
        	}
        	else{ //用户名正确+密码错误
        		$this->error('密码错误');
        	}
       	} //用户名正确结束
    }

    /**
     * 用户退出操作
     */
    public function login_out() {
        session(null);
        cookie(null);
        $this->redirect('Login/login');
        die;
    }

}
