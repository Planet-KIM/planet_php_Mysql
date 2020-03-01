<?php
namespace Ijdb\Controllers;

class Login {

	private $authentication;

	public function __construct(\PlanetHub\Authentication $authentication) {
		$this->authentication = $authentication;
	}

	public function loginForm() {
		return ['template' => 'login.html.php', 'title' => '로그인'];
	}

	public function processLogin() {
		if ($this->authentication->login($_POST['email'], $_POST['password'])) {
			header('location: /login/success');
		}
		else {
			return ['template' => 'login.html.php',
					'title' => '로그인',
					'variables' => [
							'error' => '사용자 이름/비밀번호가 유효하지 않습니다.'
						]
					];
		}
	}

	public function success() {
		return ['template' => 'loginsuccess.html.php', 'title' => '로그인 성공'];
	}

	public function error() {
		return ['template' => 'loginerror.html.php', 'title' => '로그인되지 않았습니다.'];
	}

	public function logout() {
		//unset($_SESSION);// 이방법을 사용한면 SESSION값이 초기화되지 않음.. 잘못된 방법.
		session_unset(); //이방법을 이용하면 깔끔하게 세션의 값을 초기화합니다.
		return ['template' => 'logout.html.php', 'title' => '로그아웃 되었습니다'];
	}
}
