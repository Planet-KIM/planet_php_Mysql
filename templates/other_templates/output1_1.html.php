<!doctype html>
<html>
  <head>
      <meta charset="utf-8">
      <title>IJDB - 인터넷 유머 세상</title>
  </head>
  <body>
    <?php if(isset($error)):?>
    <p>
      <?=error?>
    </p>
  <?php else: ?>
    : 텍스트 출력, 폼 출력, 데이터베이스 출력 등
    : 원하는 내용을 보여주는 부분
  <?php endif; ?>
 </body>
</html>
