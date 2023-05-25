<?php

namespace App\Core\Infrastructure\Repositories;

use App\Core\Infrastructure\Models\Seat;
use App\Core\Libraries\Bus\Repositories\SeatRepositoryInterface;

class SeatRepository implements SeatRepositoryInterface
{
    public function __construct(
        private Seat $seatModel
    ) {
    }
    public function getBusSeatIdsBySeatNums(array $seatNums, int $busId): array
    {
        return $this->seatModel
            ->where("bus_id", "=", $busId)
            ->whereIn('seat_number', $seatNums)
            ->pluck('id')->toArray();
    }
}
