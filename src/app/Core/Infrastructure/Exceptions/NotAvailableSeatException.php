<?php

namespace App\Core\Infrastructure\Exceptions;

class NotReservableSeatException extends \Exception implements \Throwable
{
    public function __construct(array $seatsNotAvailable)
    {
        parent::__construct(
            "Seat Already Reserved - " . json_encode($seatsNotAvailable),
            0
        );
    }
}
