<?php
namespace PlanetHub;

// 클래스를 만들 것입니다. 범용 함수는  $pdo 인스턴스를 필수로 전달해야합니다.
// 인수는 함수마다 다르지만 최대 4개나 되고 순서도 지켜서 삽입해주어야 합니다.
// 위의 단점을 보완해주기 위해서 함수를 클래스 안으로 옮기면 단점을 보완할 수 있습니다.
class DatabaseTable
{
  //쿼리 실행...!!
  private $pdo;
  private $table;
  private $primaryKey;

  //DatabaseTable 클래스에 생성자를 추가하고 변수를 인수로 설정하자.
  public function __construct(\PDO $pdo, string $table, string $primaryKey){
    $this->pdo = $pdo;
    $this->table = $table;
    $this->primaryKey = $primaryKey;
  }

  //글의 수를 체크해주는 함수
  public function totalJokes($pdo){
    //query() 함수로 보낼 빈 배열 생성
    //$parameters = [];

    //query() 함수를 호출할 떄 빈 $parameters 배열 전달
    $query = query($pdo, 'SELECT COUNT(*) FROM `joke`');

    $row = $query->fetch();

    return $row[0];
  }

  //query문으로 변환해서 돌려주는 것입니다.
  private function query($sql, $parameters = []) {
  	$query = $this->pdo->prepare($sql);
  	$query->execute($parameters);
  	return $query;
  }

  //글을 검색하는 함수 (getJoke version1)
  public function getJoke($pdo, $id){
    $query = $pdo->prepare('SELECT `joketext` FROM `joke` WHERE `id`= :id');
    $query->bindValue(':id', $id);
    $query->execute();

    return $query->fetch();
  }

  public function getJoke2($pdo, $id){
    //query() 함수에서 사용할 $parameters 배열 생성
    $parameters = [':id' => $id];

    //query() 함수를 호출할 떄 $parameters 배열 제공
    $query = query($pdo, 'SELECT * FROM `joke` WHERE `id`= :id', $parameters);

      $query->bindValue($name, $value);
  }

  //글 삽입 함수입니다. 이를 분리해서 간단하게 만들 수 있습니다. insertJoke() - version1
  /*function insertJoke($pdo, $joketext, $authorid){
    $query ='INSERT INTO `joke` (`joketext`, `jokedate`, `authorid`)
                  VALUES(:joketext, CURDATE(), :authorid)';

    $parameters = [':joketext' => $joketext, ':authorid' => $authorid];

    query($pdo, $query, $parameters);
  }*/

  //글 삽입 함수입니다. 이를 분리해서 간단하게 만들 수 있습니다. insertJoke() - version2
  private function insertJoke($pdo, $fields){
    $query = 'INSERT INTO `joke` (';

    foreach($fields as $key => $value){
      $query .= '`' . $key . '`,';
    }

    $query = rtrim($query, ',');

    $query .= ') VALUES (';

    foreach($fields as $key => $value){
      $query .= ':' . $key . ',';
    }

    $query = rtrim($query, ',');

    $query .= ')';

    $fields = processDates($fields);

    query($pdo, $query, $fields);
  }

  //글의 내용을 변경할 수 있게 해주는 함수입니다. .updateJoke() - version1
  /*function updateJoke($pdo, $jokeid, $joketext, $authorid){
    $parameters = [':joketext' => $joketext, ':authorid' => $authorid, ':id' => $jokeid];

    query($pdo, 'UPDATE `joke` SET `authorid` = :authorid, `joketext` = :joketext
                  WHERE `id` = :id', $parameters);
  }*/

  // 글의 내용을 변경할 수 있게 해주는 함수입니다. .updateJoke() - version3
  private function updateJoke($pdo, $fields){

    foreach($fields as $key => $value){
      $query .= '`' . $key . '` = :' . $key . ',';
    }
    $query = rtrim($query, ',');

    $query .= ' WHERE `id` = :primaryKey';

    //:primaryKey 변수 설정
    $fields['primaryKey'] = $fields['id'];

    $fields = processDates($fields);

    query($pdo, $query, $fields);
  }

  //joke의 컨텐츠를 삭제할 수 있습니다. 문제점 : 삭제시 번호가 정렬이 안됩니다. 이는 나중에 큰 문제가 될 수 있습니다. 정렬할 방법을 찾아요.ㄴ
  public function deleteJoke($pdo, $id){
    $parameters = [':id' => $id];

    query($pdo, 'DELETE FROM `joke` WHERE `id`= :id', $parameters);
  }

  public function allJokes($pdo){
    $jokes = query($pdo, 'SELECT `joke`.`id`, `joketext`, `jokedate`, `name`, `email`
                            FROM `joke` INNER JOIN `author`
                            ON `authorid`= `author`.`id`');

    return $jokes->fetchAll();
  }

  // 날짜 문자열을 반환하기 위한 함수 영역.
  private function processDates($fields){
    //배열 요소 순회
    foreach($fields as $key => $value){
      // $value가 DateTime 객체라면....
      if($value instanceof \DateTime){
        // Y-m-d H:i:s 형식으로 변환.
        $fields[$key] = $value->format('Y-m-d H:i:s');
      }
    }
    return $fields;
  }

  //이제부터는 작성자 영역 함수입니다.
  public function allAuthors($pdo){
    $authors = query($pdo, 'SELECT * FROM `author`');

    return $authors->fetchAll();
  }

  //등록자 삽입 함수입니다.
  private function insertAuthor($pdo, $fields){
    $query = 'INSERT INTO `author` (';

    foreach($fields as $key => $value){
      $query .= '`' . $key . '`,';
    }

    $query = rtrim($query, ',');

    $query .= ') VALUES (';

    foreach($fields as $key => $value){
      $query .= ':' . $key . ',';
    }

    $query = rtrim($query, ',');

    $query .= ')';

    $fields = processDates($fields);

    query($pdo, $query, $fields);
  }

  //등록자 삭제 함수입니다.
  public function deleteAuthor($pdo, $id){
    $parameters = [':id' => $id];

    query($pdo, 'DELETE FROM `author` WHERE `id` = :id', $parameters);
  }

  //table을 찾기 위한 함수입니다.
  public function findAll(){
    $result = $this->query('SELECT * FROM ' . $this->table);

    return $result->fetchAll();
  }

  //테이블 데이터 삭제.
  public function delete($id){
    $parameters = [':id' => $id];

    $this->query($pdo, 'DELETE FROM `' . $this->table . '` WHERE `' . $this->primaryKey . '` = :id', $parameters);
  }

  private function insert($fields){
    $query = 'INSERT INTO `' . $this->table . '` (';

      foreach($fields as $key => $value){
        $query .= '`' . $key . '`,';
      }

      $query = rtrim($query, ',');

      $query .= ') VALUES (';

      foreach ($fields as $key => $value) {
        $query .= ':' . $key . ',';
      }

      $query = rtrim($query, ',');

      $query .= ')';

      $fields = $this->processDates($fields);

      $this->query($query, $fields);
  }

  private function update($fields){
    $query = ' UPDATE `' . $this->table . '` SET ';

    foreach($fields as $key => $value){
      $query .= '`' . $key . '` = :' . $key . ',';
    }

    $query = rtrim($query, ',');
    $query .= ' WHERE `' . $this->primaryKey . '` = :primaryKey';

    $fields['primaryKey'] = $fields['id'];

    $fields = $this->processDates($fields);

    $this->query($query, $fields);
  }

  //테이블의 이름과 기본 키를 인수로 전달받아 특정 레코드를 검색하는 함수.
  //id로 테이블 데이터 가져오기
  public function findById($value){
    $query = 'SELECT * FROM `' . $this->table . '` WHERE `' . $this->primaryKey . '` = :value';

    $parameters = [
      'value' => $value
    ];

    $query = $this->query($query, $parameters);

    return $query->fetch();
  }

  public function find($column, $value){
    $query = 'SELECT * FROM ' . $this->table . ' WHERE ' . $column . ' = :value';

    $parameters = [
      'value' => $value
    ];

    $query = $this->query($query, $parameters);

    return $query->fetchAll();
  }

  public function total(){
    $query = $this->query('SELECT COUNT(*) FROM `' . $this->table . '`');

    $row = $query->fetch();

    return $row[0];
  }

  // version 4 약간 수정입니다. (editJoke.php 사용)
  // 등록, 수정 폼에서 공통적으로 호출하기 위한....
  // 다은 try.. catch 문을 함수로 선언하기 위한 것입니다.
  // 이를 이용하면 adojoke 항목이 없어도 됩니다.
  public function save($record){
    try{
      if($record[$this->primaryKey] == ''){
        $record[$this->primaryKey] = null;
      }
      $this->insert($record);
    }
    //지정한 id로 등록된 글 데이터가 있으면 '중복키 오류가 발생하고 update구문이 실행.'
    catch(\PDOException $e){
      $this->update($record);
    }
  }

}
