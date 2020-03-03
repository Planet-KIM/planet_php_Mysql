<?php
if(isset($_POST['joketext'])){
  try{

    include __DIR__. '/includes/DatabaseConnection.php';
    include __DIR__. '/includes/DatabaseFunctions.php';

    //version 1 입니다.
    //insertJoke($pdo, $_POST['joketext'], 1);

    //version 2 입니다.
    /*insertJoke($pdo, [
      'authorid' => 1,
      'joketext' => $_POST['joketext'],
      'jokedate' => new DateTime()
    ]);*/

    //version 3입니다.
    insert($pdo, 'joke', [
      'authorid' => 1,
      'joketext' => $_POST['joketext'],
      'jokedate' => new DateTime()
    ]);

    /* sql문을 사용하여서 joke에 글을 삽입하는 방법
    $sql = 'INSERT INTO joke SET
    joketext = :joketext,
    jokedate = CURDATE()';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':joketext', $_POST['joketext']);
    $stmt->execute();*/

    header('location: jokes.php');
  }
  catch(PDOException $e){

    $title = '오류가 발생했습니다.';

    $output = '데이터베이스 오류: ' . $e->getMessage() . ', 위치: '.
    $e->getFile() . ':' . $e->getLine();
  }
}else {
  $title = '유머 글 등록';

  ob_start();

  include __DIR__ .'/templates/addjoke.html.php';

  $output = ob_get_clean();
}
include __DIR__ . '/templates/layout.html.php';
?>
