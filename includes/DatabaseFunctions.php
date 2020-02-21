<?php
// 클래스를 만들 것입니다. 범용 함수는  $pdo 인스턴스를 필수로 전달해야합니다.
// 인수는 함수마다 다르지만 최대 4개나 되고 순서도 지켜서 삽입해주어야 합니다.
// 위의 단점을 보완해주기 위해서 함수를 클래스 안으로 옮기면 단점을 보완할 수 있습니다.

//글의 수를 체크해주는 함수
function totalJokes($pdo){
  //query() 함수로 보낼 빈 배열 생성
  //$parameters = [];

  //query() 함수를 호출할 떄 빈 $parameters 배열 전달
  $query = query($pdo, 'SELECT COUNT(*) FROM `joke`');

  $row = $query->fetch();

  return $row[0];
}

//query문으로 변환해서 돌려주는 것입니다.
function query($pdo, $sql, $parameters = []) {
	$query = $pdo->prepare($sql);
	$query->execute($parameters);
	return $query;
}

//글을 검색하는 함수 (getJoke version1)
function getJoke($pdo, $id){
  $query = $pdo->prepare('SELECT `joketext` FROM `joke` WHERE `id`= :id');
  $query->bindValue(':id', $id);
  $query->execute();

  return $query->fetch();
}

//bindValue를 분리시키고, query문 실행도 따로 만든 방법입니다. (getJoke version2) - 분리
function getJoke2($pdo, $id){
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
function insertJoke($pdo, $fields){
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

// 글의 내용을 변경할 수 있게 해주는 함수입니다. .updateJoke() - version2
function updateJoke($pdo, $fields){
  $query =' UPDATE `joke` SET ';

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
function deleteJoke($pdo, $id){
  $parameters = [':id' => $id];

  query($pdo, 'DELETE FROM `joke` WHERE `id`= :id', $parameters);
}

function allJokes($pdo){
  $jokes = query($pdo, 'SELECT `joke`.`id`, `joketext`, `jokedate`, `name`, `email`
                          FROM `joke` INNER JOIN `author`
                          ON `authorid`= `author`.`id`');

  return $jokes->fetchAll();
}

// 날짜 문자열을 반환하기 위한 함수 영역.
function processDates($fields){
  //배열 요소 순회
  foreach($fields as $key => $value){
    // $value가 DateTime 객체라면....
    if($value instanceof DateTime){
      // Y-m-d H:i:s 형식으로 변환.
      $fields[$key] = $value->format('Y-m-d H:i:s');
    }
  }
  return $fields;
}

//이제부터는 작성자 영역 함수입니다.
function allAuthors($pdo){
  $authors = query($pdo, 'SELECT * FROM `author`');

  return $authors->fetchAll();
}

//등록자 삽입 함수입니다.
function insertAuthor($pdo, $fields){
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
function deleteAuthor($pdo, $id){
  $parameters = [':id' => $id];

  query($pdo, 'DELETE FROM `author` WHERE `id` = :id', $parameters);
}

//table을 찾기 위한 함수입니다.
function findAll($pdo, $table){
  $result = query($pdo, 'SELECT * FROM `' . $table . '`');

  return $result->fetchAll();
}

//
function delete($pdo, $table, $primaryKey, $id){
  $parameters = [':id' => $id];

  query($pdo, 'DELETE FROM `' . $table . '` WHERE `' . $primaryKey . '` = :id', $parameters);
}

function insert($pdo, $table, $fields){
  $query = 'INSERT INTO `' . $table . '` (';

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

    $fields = processDates($fields);

    query($pdo, $query, $fields);
}

function update($pdo, $table, $primaryKey, $fields){
  $query = ' UPDATE `' . $table . '` SET ';

  foreach($fields as $key => $value){
    $query .= '`' . $key . '` = :' . $key . ',';
  }

  $query = rtrim($query, ',');
  $query .= ' WHERE `' . $primaryKey . '` = :primaryKey';

  $fields['primaryKey'] = $fields['id'];

  $fields = processDates($fields);

  query($pdo, $query, $fields);
}

//테이블의 이름과 기본 키를 인수로 전달받아 특정 레코드를 검색하는 함수.
function findById($pdo, $table, $primaryKey, $value){
  $query = 'SELECT * FROM `' . $table . '` WHERE `' . $primaryKey . '` = :value';

  $parameters = [
    'value' => $value
  ];

  $query = query($pdo, $query, $parameters);

  return $query->fetch();
}

function total($pdo, $table){
  $query = query($pdo, 'SELECT COUNT(*) FROM `' . $table . '`');

  $row = $query->fetch();

  return $row[0];
}

// version 4 약간 수정입니다. (editJoke.php 사용)
// 등록, 수정 폼에서 공통적으로 호출하기 위한....
// 다은 try.. catch 문을 함수로 선언하기 위한 것입니다.
function save($pdo, $table, $primaryKey, $record){
  try{
    if($record[$primaryKey] == ''){
      $record[$primaryKey] = null;
    }
    insert($pdo, $table, $record);
  }
  //지정한 id로 등록된 글 데이터가 있으면 '중복키 오류가 발생하고 update구문이 실행.'
  catch(PDOException $e){
    update($pdo, $table, $primaryKey, $record);
  }
}
