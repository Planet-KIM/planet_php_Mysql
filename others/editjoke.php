<?php
include __DIR__ . '/includes/DatabaseConnection.php';
//include __DIR__ . '/includes/DatabaseFunctions.php';
include __DIR__ . '/classes/DatabaseTable.php';

$jokesTable = new DatabaseTable($pdo, 'joke',  'id');

try{
  if(isset($_POST['joke'])){

    //version1의 함수.
    //updateJoke($pdo, $_POST['jokeid'], $_POST['joketext'], 1);

    //version2의 함수.
    /*updateJoke($pdo, [
      'id' => $_POST['jokeid'],
      'joketext' => $_POST['joketext'],
      'authorid' => 1
    ]);*/

    //version 3의 함수.
    /*update($pdo, 'joke', 'id', [
      'id' => $_POST['jokeid'],
      'joketext' => $_POST['joketext'],
      'authorid' => 1
    ]);*/

    //version 4의 함수
    /*save($pdo, 'joke', 'id', ['id' => $_POST['jokeid'],
          'joketext' => $_POST['joketext'],
          'jokedate' => new DateTime(),
          'authorid' => 1
  ]);*/
  //version 4의 함수 수정
  $joke = $_POST['joke'];
  $joke['jokedate'] = new DateTime();
  $joke['authorid'] = 1;

   $jokesTable->save($joke);


    header('location: jokes.php');
  }
  else{
    //id가 있을 때만 데이터베이스에서 글 데이터를 가져오도록 해주는 함수.
    if(isset($_GET['id'])){
      //$joke = findById($pdo, 'joke', 'id', $_GET['id']); //version 3의 일부분.
      $joke = $jokesTable->findById($_GET['id']); // version 4의 일부분. 
    }

    //version 2의 연동 함수.
    //$joke = getJoke2($pdo, $_GET['id']);

    $title = '유머 글 수정';

    ob_start();

    include __DIR__ . '/templates/editjoke.html.php';

    $output = ob_get_clean();
  }
}catch(PDOException $e){
  $title = '오류가 발생했습니다.';

  $output = '데이터 베이스 오류: ' . $e->getMessage() . ', 위치: ' .
  $e->getFile() . ':' . $e->getLine();
}

include __DIR__ . '/templates/layout.html.php';
