<?php

namespace App\Core\Infrastructure\Repositories;

use App\Core\Infrastructure\Models\Reservation;
use Illuminate\Support\Facades\DB;

class ReservationRepository
{
    public function __construct(
        private Reservation $reservationModel,
    ) {
    }

    public function list()
    {
        return $this->reservationModel->with(
            ['seat', 'passenger', 'schedule.route']
        )->get();
    }

    public function getOne(int $id): ?Reservation
    {
        return $this->reservationModel
            ->with(
                ['seat', 'passenger', 'schedule', 'schedule.route']
            )->find($id);
    }
}
