<?php

namespace app\core;

class Application
{
    public Router $router;

    public Request $request;

    public Response $response;

    public static array $config = [];

    public function __construct(array $config)
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request,$this->response);
        self::$config = $config;
    }

    public function run()
    {
        $this->router->resolve();
    }
}
