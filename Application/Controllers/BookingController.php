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
     * @param Request $request
     * @param Response $response
     * @return array
     */
    public function postNewBooking(Request $request, Response $response):array
    {
        $body = $request->getBody();
        $successOutputData = [];
        try {
            if (isset($body['tripId'])) {
                $bookingModel = new BookingModel();
                $bookingModel->loadData($body);
                $id = $bookingModel->addBooking();
                if ($id) {
                    $successOutputData = [
                        'bookingId' => $id,
                    ];
                }
            } else {
                return $response->getErrorResponse(
                    [
                        'Error : Required field tripId is missing' ,
                    ]
                );
            }

        } catch (Exception $exception) {
            return $response->getErrorResponse(
                [
                    'Exception :' . $exception->getMessage(),
                ]
            );
        }
        return $response->getSuccessResponse($successOutputData);
    }

    public function postCancelBooking(Request $request, Response $response):array
    {
        $successOutputData = [];
        $errors = [];
        try {
            $input = $request->getBody();
            if( isset($input['bookingId'])){
                if( ! isset($input['cancelled']) ){
                    $errors[] = 'Required field cancelled is missing';
                }else {
                    if((int)$input['cancelled'] > 0) {
                        $cancellation = new CancellationModel();
                        $cancellation->loadData($input);
                        $id = $cancellation->cancelBooking();
                        if ($id) {
                            $successOutputData = [
                                'cancellationId' => $id,
                            ];
                        }
                    }else {
                        $errors[] = 'cancelled value should be more than or equal to 1';
                    }
                }

            } else {
                $errors[] = 'Error : Required field bookingId is missing';
            }
        } catch (Exception $exception) {
            $errors[] = $exception->getMessage();
        }
        if (count($errors) > 0) {
            return $response->getErrorResponse(['errors' => $errors]);
        }
        return $response->getSuccessResponse($successOutputData);
    }

}
