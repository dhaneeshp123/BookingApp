<?php


namespace app\core\Controllers;


use app\core\Request;
use app\core\Response;

class Controller
{
    public $action = 'home';

    public function __construct()
    {

    }

    public function home(Request $request,Response $response)
    {
        return $response->getSuccessResponse( ['index']);
    }

}
