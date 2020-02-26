<form action="" method="post">
  <input type="hidden" name="category[id]" value="<?=$category->id ?? ''?>">
  <label for="categoryname">카테고리 명:</label>
  <input type="text" id="categoryname" name="category[name]" value="<?=$category->name ?? ''?>" />
  <input type="submit" name="submit" value="저장">
</form>