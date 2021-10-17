<?php


namespace app\core\Controllers;


use app\core\Request;
use app\core\Response;

class Controller
{
    public $action = 'home';

    /**
     * @param Request $request
     * @param Response $response
     * @return array
     */
    public function home(Request $request,Response $response)
    {
        return $response->getSuccessResponse( ['index']);
    }

}
