<?php
if(isset($error)) :
  echo '<div class="errors">' . $error . '</div>';
endif;
?>
<form method="post" action="">
  <label for="email">이메일</label>
  <input type="text" id="email" name="email">
  
  <label for="password">비밀번호</label>
  <input type="password" id="password" name="password">
  
  <input type="submit" name="login" value="로그인">
</form>

<p>계정이 없으신가요? <a href="/author/register">회원가입하려면 클릭하세요</a></p>
