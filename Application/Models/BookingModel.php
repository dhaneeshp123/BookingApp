<?php


namespace app\Application\Models;


use app\core\Model;

class BookingModel extends Model
{
    protected $username;

    protected $tripid;

    protected $numslots;

    protected $dateOfBooking;

    protected function getAttributes(): array
    {
        return ['username','tripid','numslots'];
    }
}
