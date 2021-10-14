<?php

namespace app\core;

class Application
{
    public $router;

    public $request;

    public $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request,$this->response);
    }

    public function run()
    {
        $this->router->resolve();
    }
}
