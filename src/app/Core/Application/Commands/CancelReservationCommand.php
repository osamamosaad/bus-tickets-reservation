<?php

namespace App\Core\Application\Commands;

use App\Core\Libraries\Reservation\Reservation;

class CancelReservationCommand
{
    public function __construct(
        private Reservation $reservation,
    ) {
    }

    public function execute(int $id): void
    {
        $this->reservation->cancel($id);
    }
}
