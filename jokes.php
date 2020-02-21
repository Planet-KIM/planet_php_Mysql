<!DOCTYPE html>
<html lang="ko">
    <head>
      <meta charset="utf-8">
      <title>kdw59520 web DATABASE Content</title>
    </head>
    <body>
            <?php
            try{
              //$pdo 변수를 생성하고 데이터베이스로 접속하는 인클루드 파일
              include __DIR__. '/includes/DatabaseConnection.php';
              //total joke를 세어주는 함수가 선언된 인쿨르드 파일(메소드)
              //include __DIR__. '/includes/DatabaseFunctions.php';
              include __DIR__. '/classes/DatabaseTable.php';

              //생성자를 생성....
              $jokesTable = new DatabaseTable($pdo, 'joke', 'id');
              $authorTable = new DatabaseTable($pdo, 'author', 'id');

              //테이블을 만들 sql문 영역입니다.
              /*$sql='CREATE TABLE joke(
                  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  joketext TEXT,
                  jokedate DATE NOT NULL
              )DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
              */

              /*//테이블의 내용을 확인하기 위한 것으로
              //SELECT ALL(기존의 아는 방식으로 배열을 일일히 카운트 해줘서 호출해주는 방식으로 함 // #다른방법도 참고)
              $sql = 'SELECT `joke`.`id`, `joketext`, `name`, `email`
               FROM `joke` INNER JOIN `author`
               ON `authorid` = `author`.`id`';*/

               //$jokes = allJokes($pdo); version 2

               //version 3
               //$result = findAll($pdo, 'joke');

               //version 4
               $result = $jokesTable->findAll();

               $jokes = [];
               foreach ($result as $joke) {
                 $author = $authorTable->findById($joke['authorid']);

                 $jokes[] = [
                   'id' => $joke['id'],
                   'joketext' => $joke['joketext'],
                   'jokedate' => $joke['jokedate'],
                   'name' => $author['name'],
                   'email' => $author['email']
                 ];
               }

              //$result = $pdo->query($sql);

              $title ='유머 글 목록';

              //$totalJokes = totalJokes($pdo); version 2
              //$totalJokes = total($pdo, 'joke'); //version 3
              $totalJokes = $jokesTable->total(); //version 4

              /*$count =0;

              //soulution1 fetch함수를 써서 joke배열에 담기
              while($row = $result->fetch()){
                $jokes[] = $row['joketext'];
                //$count = $count+1;
              }*/

              //solution2 foreach함수를 사용해서 즉각즉각 배열에 해당 항목을 담기.
              //해결하고 고민해볼 문제 지금 한개의 항목을 선택에서 그것을 배열에 담을 수 있다.
              //하지만 'SELECT * FROM joke' 같이 보두 호출 하여 사용자에게 보여주려고 하면 어떻게 해야 하는가?

              ob_start(); //버퍼 저장 시작

              //템플릿을 include한다. php코드가 실행되지만 결과 html은
              //브라우저로 전송되지 않고 버퍼로 저장된다.

              include __DIR__. '/templates/jokes.html.php';
              // 출력 버퍼의 내용을 읽고 $output 변수에 저장한다.
              // $output은 layout.html.php에서 사용된다.
              $output = ob_get_clean();

              //jokes.html.php를 사용하지 않을 경우 사용 이를 분리하기 위해서 jokes.html.php를 사용한 것
              /*foreach($jokes as $joke){
                $output .='<blockquote>';
                $output .='<p>';
                $output .=$joke;
                $output .='</p>';
                $output .='</blockquote>';
              }*/

              //$output ='joke 테이블 생성 완료.';
            }
            catch(PDOException $e){
              $title = '오류가 발생했습니다.';

              $output = '데이터베이스 서버에 접속할 수 없습니다: '.
              $e->getMessage() .', 위치 : ' .
              $e->getFile(). ':'. $e->getLine();
            }

            include __DIR__ .'/templates/layout.html.php';
            ?>
    </body>
</html>
