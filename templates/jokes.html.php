<p><?=$variables['totalJokes']?>개 FMU DATA가 있습니다.</p>
<?php foreach($jokes as $joke): ?>
<blockquote>
  <p>
  <?=htmlspecialchars($joke['joketext'], ENT_QUOTES, 'UTF-8')?>
  <p>
  (작성자: <a href="mailto:
  <?=htmlspecialchars($joke['email'], ENT_QUOTES, 'UTF-8'); ?>">
  <?=htmlspecialchars($joke['name'], ENT_QUOTES, 'UTF-8'); ?></a>
  작성일:
   <?php
      $date = new DateTime($joke['jokedate']);
      echo $date->format('jS F Y');  ?>)
  <?php if($userid == $joke['authorid']): ?>
    <a href="/joke/edit?id=<?=$joke['id']?>">수정</a></p>
    <form action="/joke/delete" method="post">
      <input type="hidden" name="id" value="<?=$joke['id']?>">
      <input type="submit" value="삭제">
    </form>
  <?php endif; ?>
  </p>
</blockquote>
<?php endforeach; ?>
