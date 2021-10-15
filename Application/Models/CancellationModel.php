<?php

namespace app\Application\Models;

use app\core\Model;
use Exception;
use Ramsey\Uuid\Uuid;

class CancellationModel extends Model
{
    protected string $tableName = 'cancellation';

    protected string $bookingid;

    protected int $cancelled;

    /**
     * @return string[]
     */
    protected function getAttributes(): array
    {
        return ['bookingid', 'cancelled'];
    }

    /**
     * @return string
     */
    public function getBookingid(): string
    {
        return $this->bookingid;
    }

    /**
     * @return string
     */
    public function getCancelled(): int
    {
        return $this->cancelled;
    }

    public function cancelBooking():string
    {
        $trip = new TripModel();
        $booking = new BookingModel();
        try {
            $this->getConnection()->beginTransaction();
            $booking = $booking->getById($this->getBookingid());
            $booking->getConnection()->beginTransaction();
            $trip->getConnection()->beginTransaction();
            if ($booking) {
                $bookingObj = $booking->selectForUpdate(['numofslots'], ['id' => $this->getBookingid()]);
                $tripObj = $trip->selectForUpdate(['availableslots'], ['id' => $booking->getTripid()]);
                if ( $bookingObj->numofslots >= $this->cancelled) {
                    $newAvailableSlots = $tripObj->availableslots + $this->cancelled;
                    $newNumOfSlots = $bookingObj->numofslots - $this->cancelled;
                    $this->create();
                    $trip->update(['id' => $booking->getTripid()],['availableslots' => $newAvailableSlots]);
                    $booking->update(['id' => $booking->getId()],['numofslots' => $newNumOfSlots]);
                } else {
                    throw new Exception('Canceled slots cannot be more than the slots booked');
                }

            } else {
                throw new Exception('Booking not found');
            }
            $trip->getConnection()->commit();
            $booking->getConnection()->commit();
            $this->getConnection()->commit();
        } catch (\Exception  $exception) {
            $trip->getConnection()->rollBack();
            $booking->getConnection()->rollBack();
            $this->getConnection()->rollBack();
            throw new Exception($exception->getMessage());
        }
        return $this->id;
    }

}
