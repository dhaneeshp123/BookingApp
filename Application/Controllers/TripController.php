<?php

namespace app\Application\Controllers;

use app\Application\Models\TripModel;
use app\core\Request;
use app\core\Response;

class TripController extends \app\core\Controllers\Controller
{
    public function postTrip(Request $request, Response $response)
    {
        $input = $request->getBody();
        $errors = [];
        try{
            if(isset($input['name']) && isset($input['totalslots']) &&  isset($input['availableslots'])) {
                if($input['availableslots'] > $input['totalslots']){
                    $errors[] = 'Total slots should be more than or equal to available slots';
                } else {
                    $trip = new TripModel();
                    $trip->loadData($input);
                    $id = $trip->create();
                    if ($id) {
                        return $response->getSuccessResponse(
                            ['tripId' => $id]
                        );
                    }
                    $errors[] = 'unable to create trip';
                }
            } else {
                if (!isset($input['name']))
                {
                    echo ' am hee';
                    $errors[] = 'trip name is required';
                }
                if(!isset($input['totalslots'])){
                    $errors[] = 'totalslots is required';
                }
                if(!isset($input['availableslots'])){
                    $errors[] = 'available slots is required';
                }
            }
        }catch(\Exception $exception) {
            $errors[] = $exception->getMessage();
        }
        return $response->getErrorResponse($errors);
    }

    public function getTripList(Request $request,Response $response)
    {
        $trip = new TripModel();
        $data = $trip->getTripList();
        return $response->getSuccessResponse(['result' => $data]);
    }
}
