<?php
function loadTemplate($templateFileName, $variables =[])
{
  extract($variables);

  ob_start();
  include __DIR__. '/templates/' . $templateFileName;

  return ob_get_clean();
}

try{
  include __DIR__. '/includes/DatabaseConnection.php';
  include __DIR__. '/classes/DatabaseTable.php';


  $jokesTable = new DatabaseTable($pdo, 'joke', 'id');
  $authorsTable = new DatabaseTable($pdo, 'author', 'id');

  $action = $_GET['action'] ?? 'home';

  $controllerName = $_GET['controller'] ?? 'joke';

  if($action == strtolower($action) && $controllerName == strtolower($controllerName)){

    $className = ucfirst($controllerName) . 'Controller';
    include __DIR__. '/controllers/' . $className . '.php';

    $controller = new $className($jokesTable, $authorsTable);
    $page = $controller->$action();;
    }
    else{
      http_response_code(301);
      header('locaton: index.php?controller='. strtolower($controllerName) . '&action='. strtolower($action));
    }

    $title = $page['title'];

    if(isset($page['variables'])){
      $output = loadTemplate($page['template'],
      $page['$variables']);
    }else{
      $output = loadTemplate($page['template']);
    }
}catch(PDOException $e){
  $title = '오류가 발생하였습니다.';

  $output = '데이터베이스 오류 : ' . $e->getMessage() . '. 위치 : ' .
  $e->getFile() . ':' . $e->getLine();
  
  include __DIR__. '/templates/layout.html.php';
}
