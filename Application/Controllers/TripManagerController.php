<?php

namespace app\Application\Controllers;

use app\Application\Models\BookingModel;
use app\core\Controllers\Controller;
use app\core\Request;
use app\core\Response;

class TripManagerController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @return array
     */
    public function getBookings(Request $request, Response $response)
    {
        $data = [];
        try {
            $data = (new BookingModel())->getBookings();
        } catch (\Exception $exception) {
            return $response->getErrorResponse([
                'Exception: ' . $exception->getMessage(),
            ]);
        }
        return $response->getSuccessResponse($data);
    }

}
