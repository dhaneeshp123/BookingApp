<?php

namespace test;

use app\Application\Controllers\BookingController;
use app\Application\Controllers\TripManagerController;
use app\Application\Models\BookingModel;
use app\Application\Models\CancellationModel;
use app\Application\Models\TripModel;
use app\core\Application;
use app\core\Connection;
use app\core\Request;
use app\core\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class TripBookingTest extends TestCase
{
    protected Application $app;

    protected array $trips = [];

    protected Connection $connection;

    protected function setUp(): void
    {
        parent::setUp();
        include "./config/config.php";
        /*** @var array $config * */
        $this->app = new Application($config);
        $this->connection = new Connection();
        $this->setupDatabase();
    }

    private function truncateTables()
    {
        $this->connection->execute('TRUNCATE trip');
        $this->connection->execute('TRUNCATE booking');
        $this->connection->execute('TRUNCATE cancellation');
    }

    protected function setupDatabase()
    {
        $this->truncateTables();
        include "./test/data/trip.php";
        /** @var array $trip */
        foreach ($trips as $trip) {
            $tripModel = new TripModel();
            $tripModel->loadData($trip);
            $tripModel->create();
            $this->trips[] = $tripModel;
        }
    }

    public function testBookingWithMissingTripId()
    {
        /** @var MockObject $request */
        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->method('getMethod')->willReturn('POST');
        $response = new Response();
        $response = call_user_func([new BookingController(), 'postNewBooking'], $request, $response);
        $this->assertEquals(200, $response['statusCode']);
        $this->assertEquals(Response::RESPONSE_STATUS_FAILED, $response['status']);
    }

    public function testAddNewBookingSuccess()
    {
        /** @var MockObject|Request $request */
        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->method('getMethod')->willReturn('POST');
        $tripIndex = rand(0, count($this->trips) - 1);
        /** @var TripModel $trip */
        $trip = $this->trips[$tripIndex];
        $availableSlots = $trip->getAvailableSlots();
        $request->method('getBody')->willReturn(
            ['tripId' => $trip->getId(), 'username' => 'someusername', 'numofslots' => $trip->getAvailableSlots() - 1],
        );
        $response = new Response();
        $response = call_user_func([new BookingController(), 'postNewBooking'], $request, $response);
        $this->assertEquals(Response::RESPONSE_SUCCESS_CODE, $response['statusCode']);
        $this->assertEquals(Response::RESPONSE_STATUS_SUCCESS, $response['status']);
        $this->assertNotEmpty($response['result']['bookingId']);
        $trip1 = (new TripModel())->getById($trip->getId());
        $this->assertEquals(1, $trip1->getAvailableSlots());
    }

    public function testFailedBookingByMoreThanAvailableSlots()
    {
        /** @var MockObject|Request $request */
        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->method('getMethod')->willReturn('POST');
        $tripIndex = rand(0, count($this->trips) - 1);
        /** @var TripModel $trip */
        $trip = $this->trips[$tripIndex];
        $availableSlots = $trip->getAvailableSlots();
        $request->method('getBody')->willReturn(
            ['tripId' => $trip->getId(), 'username' => 'someusername', 'numofslots' => $trip->getAvailableSlots() + 1],
        );
        $response = new Response();
        $response = call_user_func([new BookingController(), 'postNewBooking'], $request, $response);
        $this->assertEquals(Response::RESPONSE_SUCCESS_CODE, $response['statusCode']);
        $this->assertEquals(Response::RESPONSE_STATUS_FAILED, $response['status']);
        $this->assertNotEmpty($response['errors']);
        $trip1 = (new TripModel())->getById($trip->getId());
        $this->assertEquals($availableSlots, $trip1->getAvailableSlots());
    }

    public function testCancelBookingSuccess()
    {
        /** @var MockObject|Request $request */
        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->method('getMethod')->willReturn('POST');
        $tripIndex = rand(0, count($this->trips) - 1);
        /** @var TripModel $trip */
        $trip = $this->trips[$tripIndex];
        $availableSlots = $trip->getAvailableSlots();
        $bookingSlots = $availableSlots - 1;
        $canceledSlots = $bookingSlots - 1;
        $request->method('getBody')->willReturn(
            ['tripId' => $trip->getId(), 'username' => 'someusername', 'numofslots' => $bookingSlots],
        );
        $response = new Response();
        $response = call_user_func([new BookingController(), 'postNewBooking'], $request, $response);
        $bookingId = $response['result']['bookingId'];
        //try to cancel less number as booking
        $request2 = $this->getMockBuilder(Request::class)->getMock();
        $request2->method('getBody')->willReturn(
            ['bookingId' => $bookingId, 'cancelled' => $canceledSlots]
        );
        $response = new Response();
        $response = call_user_func([new BookingController(), 'postCancelBooking'], $request2, $response);
        $this->assertEquals(Response::RESPONSE_STATUS_SUCCESS, $response['status']);
        $this->assertNotEmpty($response['result']['cancellationId']);
        $trip = (new TripModel())->getById($trip->getId());
        $this->assertEquals($availableSlots - $bookingSlots + $canceledSlots, $trip->getAvailableSlots());
    }

    public function testCancelBookingFailBecauseOfCancelingMoreSlotsThanBooked()
    {
        /** @var MockObject|Request $request */
        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->method('getMethod')->willReturn('POST');
        $tripIndex = rand(0, count($this->trips) - 1);
        /** @var TripModel $trip */
        $trip = $this->trips[$tripIndex];
        $availableSlots = $trip->getAvailableSlots();
        $bookingSlots = $availableSlots - 1;
        $canceledSlots = $bookingSlots + 2;
        $request->method('getBody')->willReturn(
            ['tripId' => $trip->getId(), 'username' => 'someusername', 'numofslots' => $bookingSlots],
        );
        $response = new Response();
        $response = call_user_func([new BookingController(), 'postNewBooking'], $request, $response);
        $bookingId = $response['result']['bookingId'];
        //try to cancel less number as booking
        $request2 = $this->getMockBuilder(Request::class)->getMock();
        $request2->method('getBody')->willReturn(
            ['bookingId' => $bookingId, 'cancelled' => $canceledSlots]
        );
        $response = new Response();
        $response = call_user_func([new BookingController(), 'postCancelBooking'], $request2, $response);
        $this->assertEquals(Response::RESPONSE_STATUS_FAILED, $response['status']);
        $trip = (new TripModel())->getById($trip->getId());
        $this->assertEquals($availableSlots - $bookingSlots, $trip->getAvailableSlots());
    }

    public function testTripManager()
    {
        $data = [];
        include_once 'test/data/trip_booking_cancellation.php';
        $tripObjects = [];
        $bookingObjects = [];
        $cancellationObjects = [];
        // create trips
        $this->assertNotEmpty($data['trips']);
        $this->assertIsArray($data['trips']);
        foreach ($data['trips'] as $trip) {
            $tripObj = new TripModel();
            $tripObj->loadData($trip);
            $tripObj->create();
            $tripObjects[] = $tripObj;
        }
        $this->assertGreaterThanOrEqual(1, count($tripObjects));
        $this->assertSameSize($data['trips'], $tripObjects);
        $this->assertNotEmpty($data['bookings']);
        $this->assertIsArray($data['bookings']);
        foreach ($data['bookings'] as $booking) {
            $bookingObj = new BookingModel();
            $this->assertNotNull($tripObjects[$booking['tripIdentifier']]);
            $tripObj = $tripObjects[$booking['tripIdentifier']];
            $booking['tripid'] = $tripObj->getId();
            $bookingObj->loadData($booking);
            $bookingObj->create();
            $bookingObjects[] = $bookingObj;
        }
        $this->assertSameSize($data['bookings'],$bookingObjects);
        $this->assertIsArray($data['cancellations']);
        foreach($data['cancellations'] as $cancellation) {
            $cancellationObj = new CancellationModel();
            $this->assertNotNull($bookingObjects[$cancellation['bookingIdentifier']]);
            $bookingObj = $bookingObjects[$cancellation['bookingIdentifier']];
            $cancellation['bookingid'] = $bookingObj->getId();
            $cancellationObj->loadData($cancellation);
            $cancellationObj->create();
            $cancellationObjects[] = $cancellationObj;
        }
        /** @var MockObject|Request $request */
        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->method('getMethod')->willReturn('GET');
        $response = new Response();
        $response = call_user_func([new TripManagerController(), 'getBookings'], $request, $response);
        $this->assertEquals(Response::RESPONSE_STATUS_SUCCESS, $response['status']);
        $this->assertIsArray($response['result']);

    }

    protected function tearDownDatabase()
    {
        $this->truncateTables();
    }

    protected function tearDown(): void
    {
        $this->tearDownDatabase();
        parent::tearDown();
    }
}
