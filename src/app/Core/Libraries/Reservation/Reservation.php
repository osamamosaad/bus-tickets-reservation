<?php

namespace App\Core\Libraries\Reservation;

use App\Core\Infrastructure\Models\Reservation as ReservationModel;

class Reservation
{
    public function __construct()
    {
    }

    public function reserve(
        int $passangerId,
        int $scheduleId,
        array $seats,
    ): ReservationModel {

        $reservation = new ReservationModel();
        $reservation->passenger_id = $passangerId;
        $reservation->schedule_id = $scheduleId;
        $reservation->save();
        $reservation->seats()->attach($seats);

        return $reservation;
    }
}
