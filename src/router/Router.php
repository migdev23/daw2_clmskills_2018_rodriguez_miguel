<?php
namespace App\Routes;

class Router
{

    private $routes = [];

    public function __construct()
    {

        $this->loadRoutes();
    }

    public function loadRoutes(){

        $this->routes['GET']['/'] = ['controller' => '\\public\\ControllerPublic', 'action' => 'index'];

        $this->routes['GET']['/imgProfile/{id}'] = ['controller' => '\\public\\ControllerPublic', 'action' => 'viewPhotoProfileUid'];
        $this->routes['GET']['/imgIid/{id}'] = ['controller' => '\\public\\ControllerPublic', 'action' => 'viewImageIid'];

        $this->routes['GET']['/details/{id}'] = ['controller' => '\\public\\ControllerPublic', 'action' => 'detailsImage'];
        
        $this->routes['GET']['/login'] = [
            'controller' => '\\public\\auth\\ControllerAuth', 
            'action' => 'loginPage',
            'middlewares' => ['MiddlewareAuth' => ['notLoginArea']]
        ];

        $this->routes['POST']['/login'] = [
            'controller' => '\\public\\auth\\ControllerAuth', 
            'action' => 'loginAccess',
            'middlewares' => ['MiddlewareAuth' => ['notLoginArea']]
        ];

        $this->routes['GET']['/register'] = [
            'controller' => '\\public\\auth\\ControllerAuth', 
            'action' => 'registerPage',
            'middlewares' => ['MiddlewareAuth' => ['notLoginArea']]
        ];

        $this->routes['POST']['/register'] = [
            'controller' => '\\public\\auth\\ControllerAuth', 
            'action' => 'registerCreate',
            'middlewares' => ['MiddlewareAuth' => ['notLoginArea']]
        ];


        $this->routes['GET']['/profile'] = [
            'controller' => '\\user\\ControllerUser', 
            'action' => 'profilePage', 
            'middlewares' => ['MiddlewareAuth' => ['loginArea']]
        ];

        $this->routes['GET']['/logout'] = [
            'controller' => '\\public\\auth\\ControllerAuth', 
            'action' => 'logout',
            'middlewares' => ['MiddlewareAuth' => ['loginArea']]
        ];


        $this->routes['GET']['/newPhoto'] = [
            'controller' => '\\user\\ControllerUser', 
            'action' => 'newPhotoPage',
            'middlewares' => ['MiddlewareAuth' => ['loginArea']]
        ];

        $this->routes['POST']['/newPhoto'] = [
            'controller' => '\\user\\ControllerUser', 
            'action' => 'newPhoto',
            'middlewares' => ['MiddlewareAuth' => ['loginArea']]
        ];

        $this->routes['POST']['/deletePhoto'] = [
            'controller' => '\\user\\ControllerUser', 
            'action' => 'deletePhoto',
            'middlewares' => ['MiddlewareAuth' => ['loginArea']]
        ];

        $this->routes['GET']['/api/imgs'] = [
            'controller' => '\\api\\ImagenesApi', 
            'action' => 'imgs'
        ];

    }

    public function handleRequest()
    {

        $method = $_SERVER['REQUEST_METHOD'];

        $path = rtrim(parse_url($_SERVER['REQUEST_URI'])['path'], '/');

        $parts = explode('/', trim($path, '/'));

        $paramValue = null;

        if (is_numeric(end($parts))) {
            $paramValue = array_pop($parts);
            $path       = '/' . implode('/', $parts) . '/{id}';
        }

        if ($path == '') {
            $path = '/';
        }

        if (isset($this->routes[$method][$path])) {
            $route           = $this->routes[$method][$path];
            $controllerClass = 'App\\controllers' . $route['controller'];
            error_log($controllerClass);
            $action      = $route['action'];
            $middlewares = $route['middlewares'] ?? null;

            if (class_exists($controllerClass) && method_exists($controllerClass, $action)) {
                if (isset($middlewares) && count($middlewares) > 0) {
                    foreach ($middlewares as $controllerMid => $actionsMid) {
                        $controllerMidClass = 'App\\middlewares\\' . $controllerMid;
                        if (class_exists($controllerMidClass)) {
                            $controllerMiddleware = new $controllerMidClass();
                            foreach ($actionsMid as $actionMid) {
                                if (method_exists($controllerMidClass, $actionMid)) {
                                    if ($paramValue !== null) {
                                        $controllerMiddleware->$actionMid($paramValue);
                                    } else {
                                        $controllerMiddleware->$actionMid();
                                    }
                                }
                            }
                        }
                    }
                }

                $controller = new $controllerClass();

                if ($paramValue !== null) {
                    $controller->$action($paramValue);
                } else {
                    $controller->$action();
                }

            } else {
               
                http_response_code(404);
                echo '404';
            }

        } else {
         
            http_response_code(404);
            echo '404';
        }
    }
}

$route = new Router();

$route->handleRequest();
