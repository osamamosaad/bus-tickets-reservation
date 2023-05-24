<?php

namespace App\Core\Infrastructure\Exceptions;

class NotAvilableSeatException extends \Exception implements \Throwable
{
    public function __construct($numberOfAvailableSeats, $numberOfRequestedSeats)
    {
        parent::__construct(
            "Not available seats - " .
            "the number of available seats is: {$numberOfAvailableSeats}" .
            "the number of requested seats is: {$numberOfRequestedSeats}",
            0,
        );
    }
}
