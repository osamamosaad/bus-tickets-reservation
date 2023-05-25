<?php

namespace App\Core\Infrastructure\Exceptions;

use App\Exceptions\ValidationException;

class NotAvilableSeatException extends ValidationException implements \Throwable
{
    public function __construct($numberOfAvailableSeats, $numberOfRequestedSeats)
    {
        parent::__construct(
            "Not available seats - " .
            "the number of available seats is: {$numberOfAvailableSeats}" .
            "the number of requested seats is: {$numberOfRequestedSeats}"
        );
    }
}
