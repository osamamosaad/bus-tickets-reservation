<?php

namespace App\Core\Infrastructure\Exceptions;

use App\Exceptions\ValidationException;

class NotReservableSeatException extends ValidationException implements \Throwable
{
    public function __construct(array $seatsNotAvailable)
    {
        parent::__construct(
            "Seats Already Reserved - [ " . implode(",", $seatsNotAvailable) . " ]"
        );
    }
}
