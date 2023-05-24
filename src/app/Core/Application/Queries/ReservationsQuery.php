<?php

namespace App\Core\Application\Queries;

use App\Core\Infrastructure\Models\Reservation;
use App\Core\Infrastructure\Repositories\ReservationRepository;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReservationsQuery
{
    public function __construct(
        private ReservationRepository $reservationRepository
    ) {
    }

    public function listAll(): Collection
    {
        return $this->reservationRepository->list();
    }

    public function getOne(int $id): Reservation
    {
        if ($reservation = $this->reservationRepository->getOne($id)) {
            return $reservation;
        }

        throw new NotFoundHttpException("Reservation not found");
    }
}
