<?php
namespace PlanetHub;

class EntryPoint
{
  private $route;
  private $routes;
  private $method;

  //IjdbRoutes 객체를 인수로 전달하지 않으면 EntryPoint객체 생성못함.
  public function __construct(string $route, string $method, \PlanetHub\Routes $routes){

    $this->route = $route;
    $this->routes = $routes;
    $this->method = $method;
    $this->checkUrl();
  }

  //경로가 올바른지 확인하고 그렇지 않으면 소문자 URL로 이동시키는 함수.
  private function checkUrl(){
    if($this->route !== strtolower($this->route)){
      //URL이 전부 소문자인지 확인해주기 위한 영역입니다.
    //소문자가 아니면 301코드를 출력하여 영구 리디렉션을 나타내줍니다.
    //(잘못된 URL을 검색결과에서 제외하기 위해서입니다.)
      http_response_code(301);
      header('location: ' . strtolower($this->route));
    }
  }

  private function loadTemplate($templateFileName, $variables =[])
  {
    extract($variables);

    ob_start();
    include __DIR__. '/../../templates/' . $templateFileName;

    return ob_get_clean();
  }


  public function run(){

    $routes = $this->routes->getRoutes();
    $authentication = $this->routes->getAuthentication();

    if(isset($routes[$this->route]['login']) && isset($routes[$this->route]['login']) && !$authentication->isLoggedIn()){
      header('location: /login/error');
    }
    else if(isset($routes[$this->route]['permission']) && !$this->routes->checkPermission($routes[$this->route]['permission'])){
      header('location: /login/error');
    }
    else{
      $controller = $routes[$this->route][$this->method]['controller'];
      $action = $routes[$this->route][$this->method]['action'];

      //$page = $this->routes->callAction($this->route);
      $page = $controller->$action();

      $title = $page['title'];

      // 이렇게 하면 variables 배열에 page나 title 키가 있어도 덮어쓸 걱정이 없다.
      // loadTemplate() 함수에서 extract를 실행하고 탬플릿을 불러오기 때문이고,
      // 기존 변수와 함수 변수는 스코프가 다르기 때문입니다.
      if(isset($page['variables'])){
        $output = $this->loadTemplate($page['template'], $page['variables']); //배열에서 변수를 추출하여 변수를 한 번에 생성합니다.
      }
      else{
        $output = $this->loadTemplate($page['template']);
      }
      //include __DIR__. '/../../templates/layout.html.php';
      echo $this->loadTemplate('layout.html.php', [
        'loggedIn' => $authentication->isLoggedIn(),
        'output' => $output,
        'title' => $title
      ]);

    }
  }

}
