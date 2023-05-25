<?php

namespace App\Core\Libraries\Reservation;

use App\Core\Infrastructure\Models\Reservation as ReservationModel;

class Reservation
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELED = 'canceled';

    public function __construct()
    {
    }

    public function reserve(
        int $passangerId,
        int $scheduleId,
        array $seats,
        string $status = self::STATUS_PENDING
    ): ReservationModel {
        $reservation = new ReservationModel();
        $reservation->passenger_id = $passangerId;
        $reservation->schedule_id = $scheduleId;
        $reservation->status = $status;
        $reservation->save();
        $reservation->seats()->attach($seats);

        return $reservation;
    }
}
