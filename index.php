<?php
//version 4 이후에 코드입니다.
try{
  //include __DIR__ . '/classes/EntryPoint.php';
  //include __DIR__ . '/classes/IjdbRoutes.php';

  include __DIR__ . '/includes/autoload.php';

  //방법 3
  //route 변수가 없으면  'joke/home' 할당
  //$route = $_GET['route'] ?? 'joke/home';

  $route = ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');


  /*action으로 한번에 컨트롤 해주는 방법입니다. 방법 2
  $action = $_GET['action'] ?? 'home';
  if($action == strtolower($action)){
      $page = $jokeController->$action();
  }
  else{

    http_response_code(301);
    header('location: index.php?action='. strtolower($action));
  }
  */

  /* 일일히 if문으로 지정해주는 방법... 번거롭습니다. 방법 1
  if(isset($_GET['edit'])){
    $page = $jokeController->edit();
  }
  elseif(isset($_GET['delete'])){
    $page = $jokeController->delete();
  }
  elseif(isset($_GET['list'])){
    $page = $jokeController->list();
  }
  else{
    $page = $jokeController->home();
  }*/

  //$output = $page['output'];

  //$totalJokes = $page['totalJokes'];
  //$jokes = $page['jokes'];

  //$entryPoint = new EntryPoint($route, new IjdbRoutes());
  //namespace에 맞게 함수를 지정하는 방법....
  $entryPoint = new \PlanetHub\EntryPoint($route, $_SERVER['REQUEST_METHOD'], new \Ijdb\IjdbRoutes());
  $entryPoint->run();

}catch(PDOException $e){
  $title = '오류가 발생했습니다.';

  $output = '데이터베이스 오류 : ' . $e->getMessage() . ', 위치 : ' .
  $e->getFile() . ':' . $e->getLine();

  include __DIR__ . '/templates/layout.html.php';
}




/* version 4까지의 양식.
<!doctype html>
<html lang="ko">
    <head>
      <meta charset="utf-8">
      <title>kdw59520 WEB SITE</title>
    </head>
    <body>
        <?php

        $title = '인터넷 유머 세상';

        ob_start();

        include __DIR__. '/templates/home.html.php';

        include __DIR__. '/templates/form.html.php';

        $output = ob_get_clean();

        include __DIR__. '/templates/layout.html.php';

         ?>


    </body>
</html>*/
