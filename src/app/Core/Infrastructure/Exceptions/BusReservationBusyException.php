<?php

namespace App\Core\Infrastructure\Exceptions;

use App\Exceptions\ForbiddenException;

class BusReservationBusyException extends ForbiddenException implements \Throwable
{
    public function __construct()
    {
        parent::__construct(
            "Bus Reservation is Busy at the moment, please try again after some time",
            0
        );
    }
}
