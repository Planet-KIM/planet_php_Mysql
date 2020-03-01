<?php
namespace PlanetHub;

class Authentication{

  private $users;
  private $usernameColumn;
  private $passwordColumn;

  public function __construct(DatabaseTable $users, $usernameColumn, $passwordColumn)
  {
    session_start();
    $this->users = $users;
    $this->usernameColumn = $usernameColumn;
    $this->passwordColumn = $passwordColumn;
  }

  //사용자가 입력한 메일 주소와 비밀번호를 검사하고 로그인하는 메서드,
  //로그인 폼 제출 시 호출;
  public function login($username, $password){

    $user = $this->users->find($this->usernameColumn, strtolower($username));

    if(!empty($user) && password_verify($password, $user[0]->{$this->passwordColumn})){
      session_regenerate_id(); //해당 사용자에게 임의의 신규 세션ID가 할당됩니다.
      $_SESSION['username'] = $username;
      //중괄호로 묶으면  php언어는 괄호 안을 먼저 해석합니다.
      //비밀번호 칼럼을 먼저 읽고 $user[0]에서 해당 속성명을 찾으므로 결과를 얻을 수 있습니다.
      $_SESSION['password'] = $user[0]->{$this->passwordColumn};
      return true;
    }
    else{
      return false;
    }
  }

  //현재 사용자 로그인 상태인지, 로그인 후 비밀번호가 변경됐는지 확인하는 메서드
  //접근권한이 설정된 모든 페이지에서 호출
  public function isLoggedIn(){

    if(empty($_SESSION['username'])){
      return false;
    }

    $user = $this->users->find($this->usernameColumn, strtolower($_SESSION['username']));

    //$passwordColumn = $this->passwordColumn;

    if(!empty($user) && $user[0]->{$this->passwordColumn} === $_SESSION['password']){
      return true;
    }
    else{
      return false;
    }
  }

  public function getUser(){
    if($this->isLoggedIn()){
      return $this->users->find($this->usernameColumn, strtolower($_SESSION['username']))[0];
    }
    else{
      return false;
    }
  }

}
