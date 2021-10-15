<?php

use app\Application\Controllers\BookingController;
use app\Application\Controllers\TripManagerController;
use app\core\Application;
use app\core\Controllers\Controller;

include_once '../vendor/autoload.php';
require_once '../config/config.php';

/** @var array $config */
$app = new Application($config);

$app->router->get('/', [Controller::class, 'home']);
$app->router->post('/booking/create', [BookingController::class, 'postNewBooking']);
$app->router->post('/booking/cancel', [BookingController::class, 'postCancelBooking']);
$app->router->get('/trip-manager/bookings',[TripManagerController::class,'getBookings']);

$app->run();
