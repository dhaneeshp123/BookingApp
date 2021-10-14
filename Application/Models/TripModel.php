<?php


namespace app\Application\Models;


use app\core\Model;

class TripModel extends Model
{
    /** @var int */
   protected $availableSlots;

   protected $totalSlots;

   protected $tableName = 'trip';

    /**
     * @return int
     */
    public function getAvailableSlots(): int
    {
        return $this->availableSlots;
    }

    /**
     * @param int $availableSlots
     */
    public function setAvailableSlots(int $availableSlots): void
    {
        $this->availableSlots = $availableSlots;
    }

    /**
     * @return mixed
     */
    public function getTotalSlots()
    {
        return $this->totalSlots;
    }

    /**
     * @param mixed $totalSlots
     */
    public function setTotalSlots($totalSlots): void
    {
        $this->totalSlots = $totalSlots;
    }

}
