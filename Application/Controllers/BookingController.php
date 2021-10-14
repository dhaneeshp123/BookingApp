<?php


namespace app\Application\Controllers;


use app\core\Controllers\Controller;
use app\core\Request;
use app\core\Response;

class BookingController extends Controller
{

    public function postNewBooking(Request $request,Response $response)
    {

        $response->outputSuccessResponse([]);
    }

}
