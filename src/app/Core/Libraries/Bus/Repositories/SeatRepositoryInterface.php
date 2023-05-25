<?php

namespace App\Core\Libraries\Bus\Repositories;

interface SeatRepositoryInterface
{
    public function getBusSeatIdsBySeatNums(array $seatNums, int $busId): array;
}
