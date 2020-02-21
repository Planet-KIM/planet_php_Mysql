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


  public function list(){
    $result = $this->jokesTable->findAll();

    $jokes = [];
    foreach($result as $joke){
      $author = $this->authorsTable->findById($joke['authorid']);

      $jokes[] = [
        'id' => $joke['id'],
        'joketext' => $joke['joketext'],
        'jokedate' => $joke['jokedate'],
        'name' => $author['name'],
        'email' => $author['email'],
        'authorid' => $author['id']
      ];
    }
    $title = '유머 글 목록';

    $totalJokes = $this->jokesTable->total();

    return ['template' => 'jokes.html.php',
            'title' => $title,
            'variables' => [
              'totalJokes' => $totalJokes,
              'jokes' => $jokes,
              'userid' => $author['id'] ?? null
            ]
          ];
  }

  public function delete(){
    $author = $this->authentication->getUser();

    $Joke = $this->jokesTable->findById($_POST['id']);

    //joke 테이블의 authorid 칼럼값이 로그인 사용자의 id와 다르면  return; 메소드를 즉시 종료하고 나머지 코드 실행않함.
    if($joke['authorid'] != $author['id']){
      return;
    }

    $this->jokesTable->delete($_POST['id']);

    header('location: /joke/list');
  }

  public function saveEdit(){

    $author = $this->authentication->getUser();

    if(isset($_GET['id'])){
      $joke = $this->jokesTable->findById($_GET['id']);
      if($joke['authorid'] != $author['id']){
        return;
        //제출된 폼 데이터를 처리하기 전에 사용자 검사 기능을 추가해야한다.
      }
    }

    $joke = $_POST['joke'];
    $joke['jokedate'] = new \DateTime();
    $joke['authorid'] = $author['id'];

    $this->jokesTable->save($joke);

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
              'userid' => $author['id'] ?? null
              ]
            ];
  }
}
