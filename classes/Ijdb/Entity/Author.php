<?php
namespace Ijdb\Entity;

class Author{

  public $id;
  public $name;
  public $email;
  public $password;
  private $jokesTable;

  public function __construct(\PlanetHub\DatabaseTable $jokesTable){

    $this->jokesTable = $jokesTable;
  }

  public function getJokes(){

    return $this->jokesTable->find('authorid', $this->id);
  }

  //유머 글을 인수로 받아 authorid을 설정한 다음 데이터베이스에 저장.
  public function addJoke($joke){
    $joke['authorid'] = $this->id;
    $this->jokesTable->save($joke);
  }


}
