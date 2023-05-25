<?php

namespace App\Core\Libraries\Reservation;

use App\Core\Infrastructure\Models\Reservation as ReservationModel;

class Reservation
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELED = 'canceled';

    public function __construct(
        private ReservationDiscount $reservationDiscount
    ) {
    }

    public function reserve(
        int $passangerId,
        int $scheduleId,
        int $routeId,
        array $seats,
        float $price,
        string $status = self::STATUS_PENDING
    ): ReservationModel {
        $totalPrice = $this->reservationDiscount->calc([
            'price' => $price,
            'scheduleId' => $scheduleId,
            'routeId' => $routeId,
            'seats' => $seats,
        ]);

        $reservation = new ReservationModel();
        $reservation->passenger_id = $passangerId;
        $reservation->schedule_id = $scheduleId;
        $reservation->status = $status;
        $reservation->total_price = $totalPrice;
        $reservation->save();
        $reservation->seats()->attach($seats);

        return $reservation;
    }
}
