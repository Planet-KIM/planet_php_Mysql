<?php if(empty($joke->id) || $userid == $joke->authorid): ?>
<form action="" method="post">
  <input type="hidden" name="joke[id]" value="<?=$joke->id ?? '' ?>">
  <label for="joketext">FMU DATA을 입력해주세요: </label>
  <textarea id="joketext" name="joke[joketext]" rows="3" cols="40">
  <?=$joke->joketext ?? ''?></textarea>
  <input type="submit" name="submit" value="저장">
</form>
<?php else: ?>

<p>자신이 작성한 데이터만 수정할 수 있습니다.</p>

<?php endif; ?>
