<?php
 namespace Ijdb\Entity;

 use PlanetHub\DatabaseTable;

 class Category{

   public $id;
   public $name;
   private $jokesTable;
   private $jokeCategoriesTable;

   public function __construct(DatabaseTable $jokesTable, DatabaseTable $jokeCategoriesTable){
     $this->jokesTable = $jokesTable;
     $this->jokeCategoriesTable = $jokeCategoriesTable;
   }

   public function getJokes(){

     $jokeCategories = $this->jokeCategoriesTable->find('categoryid', $this->id, null, 10);
     $jokes = [];

     foreach($jokeCategories as $jokeCategory){
       $joke = $this->jokesTable->findById($jokeCategory->jokeid);
       if($joke){
         $jokes[] = $joke;
       }
     }

     usort($jokes,  [$this, 'sortJokes']);

     return $jokes;
   }

   private function sortjokes($a, $b){
     $aDate = new \DateTime($a->jokedate);
     $bDate = new \DateTime($b->jokedate);

     if($aDate->getTimestamp() == $bDate->getTimestamp()){
       return 0;
     }

     return $aDate->getTimestamp() > $bDate->getTimestamp() ? -1 : 1;
   }
 }
   
