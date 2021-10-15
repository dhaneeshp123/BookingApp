<?php

namespace app\Application\Controllers;

use app\Application\Models\BookingModel;
use app\Application\Models\CancellationModel;
use app\Application\Models\TripModel;
use app\core\Controllers\Controller;
use app\core\Request;
use app\core\Response;
use Exception;

class BookingController extends Controller
{
    /**
     * @param array $requestParams
     * @return bool
     */
    protected function isBookable(array $requestParams)
    {
        $trip = (new TripModel())->getById($requestParams['tripId']);
        $numOfSlots = $requestParams['numOfSlots'];
        if ($trip) {
            if ($numOfSlots > $trip->getAvailableSlots()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function postNewBooking(Request $request, Response $response)
    {
        $body = $request->getBody();
        $successOutputData = [];
        try {
            if (isset($body['tripId'])) {
                if (!$this->isBookable($body)) {
                    $response->outputErrorMessage(
                        ['Cannot book more than the available slots']
                    );
                }
                $bookingModel = new BookingModel();
                $bookingModel->loadData($body);
                $id = $bookingModel->addBooking();
                if ($id) {
                    $successOutputData = [
                        'bookingId' => $id,
                    ];
                }
            } else {
                $response->outputErrorMessage(
                    [
                        'Error : Required field tripId is missing' ,
                    ]
                );
            }

        } catch (Exception $exception) {
            $response->outputErrorMessage(
                [
                    'Exception :' . $exception->getMessage(),
                ]
            );
        }
        $response->outputSuccessResponse($successOutputData);
    }

    public function postCancelBooking(Request $request, Response $response)
    {
        $successOutputData = [];
        $errors = [];
        try {
            $input = $request->getBody();
            if( isset($input['bookingId'])){
                $cancellation = new CancellationModel();
                $cancellation->loadData($input);
                $id = $cancellation->cancelBooking();
                if ($id) {
                    $successOutputData = [
                        'cancellationId' => $id,
                    ];
                }
            } else {
                $response->outputErrorMessage(
                    [
                        'Error : Required field bookingId is missing',
                    ]
                );
            }
        } catch (Exception $exception) {
            $response->outputErrorMessage(
                [
                    'Exception :' . $exception->getMessage(),
                ]
            );
        }
        $response->outputSuccessResponse($successOutputData);
    }

}
