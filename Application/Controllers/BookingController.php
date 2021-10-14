<?php


namespace app\Application\Controllers;


use app\Application\Models\TripModel;
use app\core\Controllers\Controller;
use app\core\Request;
use app\core\Response;

class BookingController extends Controller
{

    public function postNewBooking(Request $request, Response $response)
    {
        $body = $request->getBody();
        if (isset($body['tripId'])) {
            /** @var TripModel $trip */
            $trip = (new TripModel())->getById($body['tripId']);
            $numOfSlots = $body['numOfSlots'];
            if($numOfSlots > $trip->getAvailableSlots()) {
                $response->outputErrorMessage(
                    ['Cannot book more than the available slots']
                );
            }
        }

        $response->outputSuccessResponse([]);
    }

}
