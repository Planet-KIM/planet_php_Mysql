<?php if(empty($joke->id) || $user->id == $joke->authorid || $user->hasPermission(\Ijdb\Entity\Author::EDIT_JOKES)): ?>
<form action="" method="post">
  <input type="hidden" name="joke[id]" value="<?=$joke->id ?? '' ?>">
  <label for="joketext">FMU DATA을 입력해주세요: </label>
  <textarea id="joketext" name="joke[joketext]" rows="3" cols="40">
  <?=$joke->joketext ?? ''?></textarea>

  <p>유머 카테고리 선택:</p>
  <?php foreach($categories as $category): ?>
  <?php if($joke && $joke->hasCategory($category->id)): ?>
    <input type="checkbox" checked name="category[]" value="<?=$category->id?>" />
  <?php else: ?>
    <input type="checkbox" name="category[]" value="<?=$category->id?>" />
  <?php endif; ?>
  <label><?=$category->name?></label>

  <?php endforeach; ?>
  <input type="submit" name="submit" value="저장">
</form>
<?php else: ?>

<p>자신이 작성한 데이터만 수정할 수 있습니다.</p>

<?php endif; ?>
