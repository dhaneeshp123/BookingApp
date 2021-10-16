<?php


namespace app\Application\Models;


use app\core\Model;

class TripModel extends Model
{
    public static bool $DB_UPDATE_RUNNING = false;

    protected int $availableslots;

    protected int $totalslots;

    protected string $name;

    /** @var string */
    protected string $tableName = 'trip';

    protected function getAttributes(): array
    {
        return ['availableslots','totalslots','name'];
    }

    /**
     * @return int
     */
    public function getAvailableSlots(): int
    {
        return $this->availableslots;
    }

    /**
     * @param int $availableSlots
     */
    public function setAvailableSlots(int $availableSlots): void
    {
        $this->availableslots = $availableSlots;
    }

    /**
     * @return mixed
     */
    public function getTotalSlots(): int
    {
        return $this->totalslots;
    }

    /**
     * @param mixed $totalSlots
     */
    public function setTotalSlots($totalSlots): void
    {
        $this->totalslots = $totalSlots;
    }

    /**
     * @return array
     */
    public function getTripList()
    {
        return $this->fetchAll();
    }

}
