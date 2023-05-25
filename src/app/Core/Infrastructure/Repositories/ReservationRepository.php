<?php

namespace App\Core\Infrastructure\Repositories;

use App\Core\Infrastructure\Models\Reservation;
use App\Core\Libraries\Reservation\Repositories\ReservationRepositoryInterface;
use Illuminate\Support\Facades\DB;

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

    public function getMostFrequentTrip(): array
    {
        return $this->reservationModel
            ->join('passenger', 'passenger.id', '=', 'reservation.passenger_id')
            ->join('schedule', 'schedule.id', '=', 'reservation.schedule_id')
            ->join('route', 'route.id', '=', 'schedule.route_id')
            ->select('passenger.email', 'route.name', DB::raw('count(route.id) as frequency'))
            ->groupBy('route.id', 'reservation.passenger_id')
            ->orderByDesc('frequency')
            ->get()->toArray();
    }

    public function getReservation(int $id, array $status): ?Reservation
    {
        return $this->reservationModel
            ->whereIn('status', $status)
            ->find($id);
    }
}
