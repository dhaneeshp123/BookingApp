<?php

use app\Application\Controllers\BookingController;
use app\core\Application;
use app\core\Controllers\Controller;

include_once '../vendor/autoload.php';

$app = new Application();

$app->router->get('/', [Controller::class,'home']);
$app->router->post('/booking/addnew',[BookingController::class,'postNewBooking']);

$app->run();
