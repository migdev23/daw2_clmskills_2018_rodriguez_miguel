<?php

namespace App\Routes;

use App\controllers\ControllerPublic;
use App\middlewares\MiddlewareAuth;

class Router
{

    private $routes = [];

    public function __construct()
    {

        $this->loadRoutes();
    }

    public function loadRoutes(){

        $this->routes['GET']['/']  = ['controller' => '\\public\\ControllerPublic', 'action' => 'index'];

        $this->routes['GET']['/{id}']  = [
            'controller' => '\\public\\ControllerPublic',
            'action' => 'indexParametro',
            'middlewares' => ['MiddlewareAuth' => ['loginArea', 'notLoginArea']]
        ];

    }


    public function handleRequest(){

        $method = $_SERVER['REQUEST_METHOD'];

        $path = rtrim(parse_url($_SERVER['REQUEST_URI'])['path'], '/');

        $parts = explode('/', trim($path, '/'));

        $paramValue = null;

        if (is_numeric(end($parts))) {
            $paramValue = array_pop($parts);
            $path = '/' . implode('/', $parts) . '{id}';
        }


        if ($path == '') {
            $path = '/';
        }

        error_log($path . ':' . $method);

        if (isset($this->routes[$method][$path])) {
            $route = $this->routes[$method][$path];
            $controllerClass = 'App\\controllers' . $route['controller'];
            error_log($controllerClass);
            $action = $route['action'];
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
