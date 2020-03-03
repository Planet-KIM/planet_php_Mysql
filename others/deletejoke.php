<?php
  try{

    include __DIR__. '/includes/DatabaseConnection.php';
    //include __DIR__. '/includes/DatabaseFunctions.php';
    include __DIR__. '/classes/DatabaseTable.php';
    
    $jokesTable = new DatabaseTable($pdo, 'joke', 'id');

    //version 1의 함수
    //deleteJoke($pdo,  $_POST['id']);


    //version 2의 함수
    /* 분리시키기 전에 한공간에서 처리함.
    $sql = 'DELETE FROM `joke` WHERE `id` = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_POST['id']);
    $stmt->execute();*/
    
    //version 3의 함수 
    //delete($pdo, 'joke', 'id', $_POST['id']);
    
    //version 4의 함수
    $jokesTable->delete($_POST['id'])

    header('location: jokes.php');
  }
  catch(PDOException $e){

    $title = '오류가 발생했습니다.';

    $output = '데이터베이스 오류: ' . $e->getMessage() . ', 위치: '.
    $e->getFile() . ':' . $e->getLine();
  }

include __DIR__ . '/templates/layout.html.php';
