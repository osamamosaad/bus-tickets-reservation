<?php

namespace App\Core\Libraries\Bus;

class BusSchedule
{
    public function __construct()
    {
    }

    public function addBusSchedule(
        int $busId,
        int $routeId,
        string $departureTime,
        string $arrivalTime,
        int $price,
        int $availableSeats,
    ): void {
        //  its just an example for what should be done in Bus laibrary
    }
}
