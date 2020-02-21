<?php
$pdo = new PDO('mysql:host=localhost;dbname=ijdb',
  'kdw59520','rlaehdnjs12!');
  //$output = '데이터베이스 접속 성공.';

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
