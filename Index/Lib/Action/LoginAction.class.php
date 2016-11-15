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
	 * 登录表单处理
	 */
	Public function runLogin(){
		if (!$this->isPost()) {
			halt('页面不存在');
		}

		//提取表单内容
		$account = $this->_post('account');
		$pwd = $this->_post('pwd', 'md5');

		$where = array('account' => $account);

		$user = M('user')->where($where)->find();

		if (!$user || $user['password'] != $pwd) {
			$this->error('用户名或者密码不正确');
		}

		if ($user['lock']) {
			$this->error('用户被锁定');
		}

		//处理下一次自动登录
		if (isset($_POST['auto'])) {
			$account = $user['account'];
			$ip = get_client_ip();
			$value = $account . '|' . $ip;
			$value = encryption($value);
			@setcookie('auto', $value, C('AUTO_LOGIN_TIME'), '/');
		}

		//登录成功写入SESSION并且跳转到首页
		session('uid', $user['id']);

		header('Content-Type:text/html;Charset=UTF-8');
		redirect(__APP__, 3, '登录成功，正在为您跳转...');
	}

	/**
	 * 注册页面
	 */
	public function register(){

		$this -> display();
	}	

	/**
	 * 注册表单处理
	 */
	Public function runRegis () {
		if (!$this->isPost()) {
			halt('页面不存在');
		}
		if ($_SESSION['verify'] != md5($_POST['verify'])) {
			$this->error('验证码错误');
		}
		if ($_POST['pwd'] != $_POST['pwded']) {
			$this->error('两次密码不一致');
		}

		//提取POST数据
		$data = array(
			'account' => $this->_post('account'),//没有第二个参数的话，默认为htmlspecialchars
			'password' => $this->_post('pwd', 'md5'),
			'registime' => $_SERVER['REQUEST_TIME'],//时间比time()的值快
			'userinfo' => array(//此键名表示要关联的表名
				'username' => $this->_post('uname')
				)
			);

		$id = D('UserRelation')->insert($data);
		if ($id) {
			//插入数据成功后把用户ID写SESSION
			session('uid', $id);

			//跳转至首页
			header('Content-Type:text/html;Charset=UTF-8');
			redirect(__APP__, 3, '注册成功，正在为您跳转...');//跳转地址，几秒后跳转，提示信息
		} else {
			$this->error('注册失败，请重试...');
		}
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
		
		$where = array('account' => $account);
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
		
		$where = array('username' => $username);
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