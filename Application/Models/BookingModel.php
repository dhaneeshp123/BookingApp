<?php


namespace app\Application\Models;


use app\core\Model;
use Exception;
use Ramsey\Uuid\Uuid;

class BookingModel extends Model
{
    protected string $username;

    protected string $tripid;

    protected int $numofslots;

    protected string $bookingdate;

    protected string $tableName = 'booking';

    protected function getAttributes(): array
    {
        return ['username', 'tripid', 'numofslots'];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function addBooking(): string
    {
        $trip = new TripModel();
        try {

            $this->connection->beginTransaction();
            $trip->getConnection()->beginTransaction();
            if($this->numofslots < 1) {
                throw new Exception('The slot booked should be more than 1');
            }
            $tripObj = $trip->selectForUpdate(['availableslots'],['id' => $this->tripid]);
            if ($tripObj) {
                $availableSlots = $tripObj->availableslots;
                if ($availableSlots < $this->numofslots)
                {
                    throw new Exception('Cannot book more slots than the available');
                }
                $this->create();
                $newAvailableSlots = $availableSlots - $this->numofslots;
                $trip->update(['id' => $this->tripid],['availableslots' => $newAvailableSlots]);

            } else {
                throw new Exception('Record not found for specific tripId');
            }
            $trip->getConnection()->commit();
            $this->connection->commit();
        } catch (\Exception $exception) {
            $trip->getConnection()->rollBack();
            $this->connection->rollBack();
            throw new \Exception($exception->getMessage());
        }
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getTripid(): string
    {
        return $this->tripid;
    }

    /**
     * @return int
     */
    public function getNumofslots(): int
    {
        return $this->numofslots;
    }

    /**
     * @return string
     */
    public function getBookingDate(): string
    {
        return $this->bookingdate;
    }

    /**
     * @return array[]
     */
    public function getBookings():array
    {
        $bookings = $this->fetchAll();
        $bookingData = [];
        foreach($bookings as $booking){
            $cancellations = (new CancellationModel())->findBy(['bookingid' => $booking['id']]);
            $trip = (new TripModel())->findOneBy(['id' =>$booking['tripid']]);
            $booking['cancellations'] = $cancellations;
            $booking['trip'] = $trip;
            $bookingData[] = [
                'booking' => $booking,
            ];
        }
        return ['bookings' => $bookingData];
    }

}
