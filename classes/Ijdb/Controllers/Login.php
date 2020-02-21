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
		unset($_SESSION);
		return ['template' => 'logout.html.php', 'title' => '로그아웃 되었습니다'];
	}
}
