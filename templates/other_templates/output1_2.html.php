<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>IJDB - 인터넷 유머세상</title>
  </head>
  <body>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="jokes.php">유머 글 목록</a></li>
      <ul>
    </nav>
    
    <main>
      <?php if(isset($error)): ?>
      <p>
        <?=$error?>
      </p>
    <?php else: ?>
      : 텍스트 출력, 폼 출력, 데이터베이스 출력 등
      : 원하는 내용을 보여주는 부분
    <?php endif; ?>
   </main>
   
   <footer>
     (c) kdw59520 DATABASE 2020
   </footer>
 </body>
</html>