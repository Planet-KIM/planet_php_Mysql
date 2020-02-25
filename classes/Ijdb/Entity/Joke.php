<?php
namespace Ijdb\Entity;

class Joke{

  public $id;
  public $authorid;
  public $jokedate;
  public $joketext;
  private $authorsTable;
  private $author;

  public function __construct(\PlanetHub\DatabaseTable $authorsTable){

    $this->authorsTable = $authorsTable;
  }

  //현재 유머글의 작성자를 반환
  public function getAuthor(){
    //클래스 변수 author에 값이 있는지 확인한다.
    //없으면 데이터베이스에서 작성자 데이터를 가져와 저장한다.
    //author 변수를 반환한다
    if(empty($this->author)){
      $this->author = $this->authorsTable->findById($this->authorid);
    }
    return $this->author;
  }

}
