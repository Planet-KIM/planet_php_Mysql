<?php
namespace Ijdb;

//Routes인터페이스를 상속하도록 지정.
//인터페이스는 범용 프레임워크를 구축하는데 용이합니다.
//인터페이스를 미리만들고 구현하면 프레임워크 코드와 프로젝트 전용코드가 서로 충돌하지 않게됩나다.
//인터페이스는 프레임워크 코드와 프로젝트 전용 코드를 연결하는 중요한 다리입니다.
class IjdbRoutes implements \PlanetHub\Routes{

  private $authorsTable;
  private $jokesTable;
  private $authentication;

  public function __construct(){
    include __DIR__ . '/../../includes/DatabaseConnection.php';

    $this->jokesTable = new \PlanetHub\DatabaseTable($pdo, 'joke', 'id');
    $this->authorsTable = new \PlanetHub\DatabaseTable($pdo, 'author', 'id');
    $this->authentication = new \PlanetHub\Authentication($this->authorsTable, 'email', 'password');
  }

  public function getRoutes(): array
  {
    include __DIR__. '/../../includes/DatabaseConnection.php';
    //include __DIR__. '/../classes/DatabaseTable.php';

    $jokeController = new \Ijdb\Controllers\Joke($this->jokesTable, $this->authorsTable, $this->authentication);
    $authorController = new \Ijdb\Controllers\Register($this->authorsTable);

    $loginController = new \Ijdb\Controllers\Login($this->authentication);

    $routes = [
  			'author/register' => [
  				'GET' => [
  					'controller' => $authorController,
  					'action' => 'registrationForm'
  				],
  				'POST' => [
  					'controller' => $authorController,
  					'action' => 'registerUser'
  				]
  			],
  			'author/success' => [
  				'GET' => [
  					'controller' => $authorController,
  					'action' => 'success'
  				]
  			],
  			'joke/edit' => [
  				'POST' => [
  					'controller' => $jokeController,
  					'action' => 'saveEdit'
  				],
  				'GET' => [
  					'controller' => $jokeController,
  					'action' => 'edit'
  				],
  				'login' => true

  			],
  			'joke/delete' => [
  				'POST' => [
  					'controller' => $jokeController,
  					'action' => 'delete'
  				],
  				'login' => true
  			],
  			'joke/list' => [
  				'GET' => [
  					'controller' => $jokeController,
  					'action' => 'list'
  				]
  			],
  			'login/error' => [
  				'GET' => [
  					'controller' => $loginController,
  					'action' => 'error'
  				]
  			],
  			'login/success' => [
  				'GET' => [
  					'controller' => $loginController,
  					'action' => 'success'
  				]
  			],
  			'login' => [
  				'GET' => [
  					'controller' => $loginController,
  					'action' => 'loginForm'
  				],
  				'POST' => [
  					'controller' => $loginController,
  					'action' => 'processLogin'
  				]
  			],
        'logout' => [
          'GET' => [
            'controller' => $loginController,
            'action' => 'logout'
          ]
        ],
  			'' => [
  				'GET' => [
  					'controller' => $jokeController,
  					'action' => 'home'
  				]
  			]
  		];
    /*$method = $_SERVER['REQUEST_METHOD'];

    $controller = $routes[$route][$method]['controller'];
    $action = $routes[$route][$method]['action'];*/

    //return $controller->$action();
    return $routes;
  }

  public function getAuthentication(): \PlanetHub\Authentication{
    return $this->authentication;
  }

}
