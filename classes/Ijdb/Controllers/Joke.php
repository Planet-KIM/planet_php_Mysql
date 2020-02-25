<?php
namespace Ijdb\Controllers;
use \PlanetHub\DatabaseTable;
use \PlanetHub\Authentication;
// 이곳에 컨트롤러 코드를 전부 옮겨 두어서 jokes, editjoke, deletejoke.php 가 필요없어집니다.
class Joke{
  private $authorsTable;
  private $jokesTable;

  public function __construct(DatabaseTable $jokesTable, DatabaseTable $authorsTable, Authentication $authentication){
    $this->jokesTable = $jokesTable;
    $this->authorsTable = $authorsTable;
    $this->authentication =$authentication;
  }

  public function home(){
    $title = '인터넷 유머 세상';

    return ['template' => 'home.html.php', 'title' => $title];
  }

  //oop도입
  //1. 데이터베이스에서 모든 유머글을 검색한다.
  //2. 각 유머글을 반복문에서 차례대로 읽는다.
  //   - 작성자를 검색한다
  //   - 글과 작성자 정보가 모두 저장된 새 배열을 생성한다.
  //3. 배열을 탬플릿으로 전달.
  public function list(){
    $jokes = $this->jokesTable->findAll();

    $jokes = [];
    foreach($result as $joke){
      $author = $this->authorsTable->findById($joke->authorid);
      $jokes[] = [
        'id' => $joke->id,
        'joketext' => $joke->joketext,
        'jokedate' => $joke->jokedate,
        'name' => $author->name,
        'email' => $author->email
        //'authorid' => $author['id']
      ];
    }
    $title = '유머 글 목록';

    $totalJokes = $this->jokesTable->total();

    return ['template' => 'jokes.html.php',
            'title' => $title,
            'variables' => [
              'totalJokes' => $totalJokes,
              'jokes' => $jokes,
              'userid' => $author->id ?? null
            ]
          ];
  }

  public function delete(){
    $author = $this->authentication->getUser();

    $Joke = $this->jokesTable->findById($_POST['id']);

    //joke 테이블의 authorid 칼럼값이 로그인 사용자의 id와 다르면  return; 메소드를 즉시 종료하고 나머지 코드 실행않함.
    if($joke->authorid != $author->id){
      return;
    }

    $this->jokesTable->delete($_POST['id']);

    header('location: /joke/list');
  }

  public function saveEdit(){

    //$author = $this->authentication->getUser();
    //$authorObject = new \Ijdb\Entity\Author($this->jokesTable);

    //배열데이터를 객체로 그저 복사하지 않기 위해서 수정합니다.
    $author = $this->authentication->getUser();

    //베열데이터를 객체로 복사하는 코드입니다. 이는 코드도 길어지고 비효율적입니다.
    /*$authorObject->id = $author['id'];
    $authorObject->name = $author['name'];
    $authorObject->email = $author['email'];
    $authorObject->password = $author['password'];*/


    if(isset($_GET['id'])){
      $joke = $this->jokesTable->findById($_GET['id']);
      if($joke['authorid'] != $author['id']){
        return;
        //제출된 폼 데이터를 처리하기 전에 사용자 검사 기능을 추가해야한다.
      }
    }

    $joke = $_POST['joke'];
    $joke['jokedate'] = new \DateTime();
    //$joke['authorid'] = $author['id'];

    //$this->jokesTable->save($joke);
    $author->addJoke($joke);

    //돌아갈 폼 양식입니다.
    header('location: /joke/list');
  }


  public function edit(){
    /*if(isset($_POST['joke'])){
      $joke = $_POST['joke'];
      $joke['jokedate'] = new \DateTime();
      $joke['authorid'] = 1;

      $this->jokesTable->save($joke);

      header('location: /joke/list');
    }
    else{
    }*/
    $author = $this->authentication->getUser();

    if(isset($_GET['id'])){
      $joke = $this->jokesTable->findById($_GET['id']);
    }

    $title = '유머 글 수정';

    return ['template' => 'editjoke.html.php',
            'title' => $title,
            'variables' => [
              'joke' => $joke ?? null,
              'userid' => $author->id ?? null
              ]
            ];
  }
}
