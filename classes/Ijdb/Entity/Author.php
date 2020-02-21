<?php
namespace Ijdb\Entit;

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
  
}