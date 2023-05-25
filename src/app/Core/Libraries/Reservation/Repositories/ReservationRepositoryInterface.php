<?php

namespace App\Core\Libraries\Reservation\Repositories;

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
}
