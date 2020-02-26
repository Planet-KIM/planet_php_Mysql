<?php
namespace Ijdb\Controllers;

class Category{
  
  private $categoriesTable;
  
  public function __construct(\PlanetHub\DatabaseTable $categoriesTable){
    $this->categoriesTable = $categoriesTable;
  }
  
  public function edit(){
    if(isset($_GET['id'])){
      $category = $this->categoriesTable->findById($_GET['id']);
    }
    
    $title = '카테고리 수정';
    return ['template' => 'editcategory.html.php',
            'title' => $title,
            'variables' => [ 
                'category' => $category ?? null
              ]
            ];
  }
  
  public function saveEdit(){
    $category =$_POST['category'];
    
    $this->categoriesTable->save($category);
    
    header('location: /category/list'); 
  }
  
  public function list(){
    $categories = $this->categoriesTable->findAll();
    
    $title = '카테고리 목록';
    
    return ['template' => 'categories.html.php',
            'title' => $title,
            'variables' => [
              'categories' => $categories
            ]
          ];
  }
  
  public function delete(){
    
    $this->categoriesTable->delete($_POST['id']);
    
    header('location: /category/list');
  }
}