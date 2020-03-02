<div class="jokelist">
<ul class="categories">
  <?php foreach($categories as $category): ?>
  <li><a href="/joke/list?category=<?=$category->id?>"><?=$category->name?></a><li>
  <?php endforeach; ?>
</ul>

<div class="jokes">
<p><?=$variables['totalJokes']?>개 FMU DATA가 있습니다.</p>

<?php foreach($jokes as $joke): ?>
<blockquote>
  
  <?=(new \PlanetHub\Markdown($joke->joketext))->toHtml()?>
  
  (작성자: <a href="mailto:
  <?=htmlspecialchars($joke->getAuthor()->email, ENT_QUOTES, 'UTF-8'); ?>">
  <?=htmlspecialchars($joke->getAuthor()->name, ENT_QUOTES, 'UTF-8'); ?></a>
  작성일:
   <?php
      $date = new DateTime($joke->jokedate);
      echo $date->format('jS F Y');  ?>)
      <?php if ($user): ?>
        <?php if($user->id == $joke->authorid || $user->hasPermission(\Ijdb\Entity\Author::EDIT_JOKES)): ?>
          <a href="/joke/edit?id=<?=$joke->id?>">수정</a>
        <?php endif; ?>
        <?php if($user->id == $joke->authorid || $user->hasPermission(\Ijdb\Entity\Author::DELETE_JOKES)): ?>
          <form action="/joke/delete" method="post">
            <input type="hidden" name="id" value="<?=$joke->id?>">
            <input type="submit" value="삭제">
          </form>
        <?php endif; ?>
      <?php endif; ?>
  
</blockquote>
<?php endforeach; ?>
</div>
