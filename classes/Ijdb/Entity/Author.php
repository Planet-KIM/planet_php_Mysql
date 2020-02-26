<?php
namespace Ijdb\Entity;

class Author{

  public $id;
  public $name;
  public $email;
  public $password;
  private $jokesTable;

  public function __construct(\PlanetHub\DatabaseTable $jokeTable){

    $this->jokesTable = $jokeTable;
  }

  public function getJokes(){

    return $this->jokesTable->find('authorid', $this->id);
  }

  //유머 글을 인수로 받아 authorid을 설정한 다음 데이터베이스에 저장.
  public function addJoke($joke){
    $joke['authorid'] = $this->id;

    /* 첫번쨰 방법인데... 문제 query가 두번쓰여서 성능 저하, 새로등록한 유저의 id를 몰름.
    // Database에 유머 글 저장..
    $this->jokesTable->save($joke);

    //객체로 새 유머 글 가져오기
    return $this->jokesTable->findById($id);*/

    // 두번쨰 방법....
    return $this->jokesTable->save($joke);
  }


}
