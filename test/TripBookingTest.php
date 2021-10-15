<?php

namespace test;

use app\Application\Controllers\BookingController;
use app\Application\Models\TripModel;
use app\core\Application;
use app\core\Connection;
use app\core\Request;
use app\core\Response;
use Http\Mock\Client;
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
        include_once "./config/config.php";
        /*** @var array $config **/
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
        include_once "./test/data/trip.php";
        /** @var array $trip */
        foreach($trips as $trip)
        {
            $tripModel = new TripModel();
            $tripModel->loadData($trip);
            $tripModel->create();
            $this->trips[] = $tripModel;
        }
    }

    public function testGetAllBookings()
    {
            /** @var MockObject $request */
            $request = new RequestInterface()
             $request->method('getMethod')->willReturn('POST');

            $response = new Response();
            $client = new Client();

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
