<?php

namespace App\Core\Infrastructure\Repositories;

use App\Core\Infrastructure\Models\Reservation;
use App\Core\Libraries\Reservation\Repositories\ReservationRepositoryInterface;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function __construct(
        private Reservation $reservationModel,
    ) {
    }

    public function list()
    {
        return $this->reservationModel->with(
            ['seats', 'passenger', 'schedule.route']
        )->get();
    }

    public function getOne(int $id): ?Reservation
    {
        return $this->reservationModel
            ->with(
                ['seats', 'passenger', 'schedule', 'schedule.route']
            )->find($id);
    }

    public function getReservedSeats($scheduleId): array
    {
        return $this->reservationModel
            ->select(
                "seat.id",
                "seat.seat_number",
            )
            ->join("reservation_seat", "reservation_seat.reservation_id", "=", "reservation.id")
            ->join("seat", "seat.id", "=", "reservation_seat.seat_id")
            ->where('schedule_id', "=", $scheduleId)
            ->where('status', "=", "approved")
            ->get()
            ->toArray();
    }
}
