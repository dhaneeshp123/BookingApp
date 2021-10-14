<?php

namespace app\core;

use app\core\Controllers\Controller;

class Router
{
    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @var Request
     */
    protected $request;

    protected $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback) {
            if (is_array($callback) && count($callback) > 0) {
                /** @var Controller $controller */
                $controller = new $callback[0];
                if(isset($callback[1])){
                    $controller->action = $callback[1];
                }
                $callback[0] = $controller;
            }
            call_user_func($callback,$this->request,$this->response);
        } else {
            $this->response->getPageNotFoundResponse();
        }
    }

}
