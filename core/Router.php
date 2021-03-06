<?php

namespace app\core;

use app\core\Controllers\Controller;

class Router
{

    protected array $routes = [];

    protected Request $request;

    protected Response $response;

    /**
     * Router constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param $path
     * @param $callback
     */
    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    /**
     * @param $path
     * @param $callback
     */
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
            $this->response->outputJson(call_user_func($callback,$this->request,$this->response));
        } else {
            $this->response->getPageNotFoundResponse();
        }
    }

}
