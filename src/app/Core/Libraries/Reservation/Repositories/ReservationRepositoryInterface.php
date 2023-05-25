<?php

namespace App\Core\Libraries\Reservation\Repositories;

use App\Core\Infrastructure\Models\Reservation;

interface ReservationRepositoryInterface
{
    /**
     * Reserved seats is the active reservation for the seats
     *
     * @param int $scheduleId
     *
     * @return array
     */
    public function getReservedSeats($scheduleId): array;

    public function getMostFrequentTrip(): array;

    public function getReservation(int $id, array $status): ?Reservation;
}
