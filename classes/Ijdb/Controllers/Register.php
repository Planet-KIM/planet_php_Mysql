<?php
namespace Ijdb\Controllers;
use \PlanetHub\DatabaseTable;

class Register{

  private $authorsTable;

  public function __construct(DatabaseTable $authorsTable){
    $this->authorsTable = $authorsTable;
  }

  public function registrationForm(){
    return ['template' => 'register.html.php',
            'title' => '사용자 등록'];
  }

  public function registerUser(){
    $author = $_POST['author'];

    // 데이터는 처음부터 유효하다고 가정
    $valid = true;
    $errors = [];

    // 하지만 항목이 빈 값이면....
    // $valid에 false 할당
    if(empty($author['name'])){
      $valid = false;
      $errors[] = "이름을 입력해야 합니다.";
    }

    if(empty($author['email'])){
      $valid = false;
      $errors[] = "이메일을 입력해야 합니다.";
    }
    else if(filter_var($author['email'], FILTER_VALIDATE_EMAIL) == false){
      //유효성 검사입니다.(이메일)
      $valid = false;
      $errors[] = '유효하지 않은 이메일 주소입니다.';
    }
    else{
      //이메일 주소가 빈 값이 아니고 유효하다면...
      //이메일 주소를 소문자로 변환
      $author['email'] = strtolower($author['email']);

      //$author['email']을 소문자로 검색
      if(count($this->authorsTable->find('email', $author['email'])) > 0){
        $valid = false;
        $errors[] = '이미 가입된 이메일 주소입니다.';
      }
    }

    if(empty($author['password'])){
      $valid = false;
      $errors[] = "비밀번호를 입력해야 합니다.";
    }

    // $valid가 true라면 빈 항목이 없으므로 데이터를 추가할 수 있음
    if($valid == true){
      //데이터베이스에 저장하기 전에 비밀번호를 안전하게 해시화해서 보관해야함. (보안)
      $author['password'] = password_hash($author['password'], PASSWORD_DEFAULT);
      //폼이 전송되면 $author 변수는 소문자 메일과 비밀번호 해시값을 포함
      $this->authorsTable->save($author);

      header('Location: /author/success');
    }
    else{
      //데이터가 유효하지 않으면 폼을 다시 출력
      return ['template' => 'register.html.php',
       'title' => '사용자 등록',
       'variables' => [
         'errors' => $errors,
         'author' => $author
         ]
       ];
    }
  }

  public function success(){
    return ['template' => 'registersuccess.html.php',
            'title' => '등록 성공'];
  }
  
  //등록된 사용자 목록을 가져와 탬플릿으로 전달....
  public function list(){
    $authors = $this->authorsTable->findAll();
    
    return ['template' => 'authorlist.html.php',
            'title' => '사용자 목록',
            'variables' => [
              'authors' => $authors
            ]
          ];
  }
  
  public function permissions(){
    $author = $this->authorsTable->findById($_GET['id']);
    
    $reflected = new \ReflectionClass('\Ijdb\Entity\Author');
    $constants = $reflected->getConstants();
    
    return ['template' => 'permissions.html.php',
            'title' => '권한 수정',
            'variables' => [
                'author' => $author,
                'permissions' => $constants
              ]
            ];
  }
  
  //선택한 체크박스 값을 모두 합산하고 사용자 권한을 이진 구조로 표현.
  public function savePermissions(){
    $author = [
      'id' => $_GET['id'],
      //아무권한도 선택하지 않으면 키가 없기에 빈배열을 자동으로 할당 
      'permissions' => array_sum($_POST['permissions'] ?? []) //arry_sum() 모든 배열 원소의 합을 계산.
    ];
    
    $this->authorsTable->save($author);
    
    header('location: /author/list');
  }
}
