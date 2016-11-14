<?php
/**
 * 注册与登录控制器
 */
class LoginAction extends Action {

	/**
	 * 登录页面
	 */
	public function login(){

		$this -> display();
	}

	/**
	 * 注册页面
	 */
	public function register(){

		$this -> display();
	}	

	/**
	 * 获得验证码
	 */
	public function verify(){

		import('ORG.Util.Image');

		Image::buildImageVerify(4,1,'png');

	}

	/**
	 * 注册时异步验证账号是否已存在
	 */
	public function checkAccount(){

		if(!$this -> isAjax()){
			halt('页面不存在');//tp 抛出错误页面的方法
		}

		$account = $this -> _post('account');
		// ==
		// $account = $this -> htmlspecialchars($_POST['account']);
		
		$where = array('account',$account);
		if(M('user') -> where($where) -> getField('id')){
			echo 'false';
		}else{
			echo 'true';
		}
	}

	/**
	 * 注册时异步验证昵称是否已存在
	 */
	public function checkUname(){

		if(!$this -> isAjax()){
			halt('页面不存在');
		}

		$username = $this -> _post('uname');
		
		$where = array('username',$username);
		if(M('userinfo') -> where($where) -> getField('id')){
			echo 'false';
		}else{
			echo 'true';
		}
	}	

	/**
	 * 注册时异步验证验证码是否正确
	 */
	public function checkVerify(){

		if(!$this -> isAjax()){
			halt('页面不存在');
		}

		$verify = $this -> _post('verify');	
		
		if($_SESSION['verify'] != md5($verify)){
			echo "false";
		}else{	
			echo "true";
		}
	}	
}