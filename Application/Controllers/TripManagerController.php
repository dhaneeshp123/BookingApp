<?php

namespace app\Application\Controllers;

use app\Application\Models\BookingModel;
use app\core\Controllers\Controller;
use app\core\Request;
use app\core\Response;

class TripManagerController extends Controller
{
    public function getBookings(Request $request, Response $response)
    {
        $data = [];
        try {
            $data = (new BookingModel())->getBookings();
        } catch (\Exception $exception) {
            $response->outputErrorMessage([
                'Exception: ' . $exception->getMessage(),
            ]);
        }
        $response->outputSuccessResponse($data);
    }

}
