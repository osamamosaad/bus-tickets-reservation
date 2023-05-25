<?php

namespace App\Core\Libraries\Reservation;

use App\Core\Infrastructure\Exceptions\NotFoundException;
use App\Core\Infrastructure\Models\Reservation as ReservationModel;
use App\Core\Libraries\Reservation\Repositories\ReservationRepositoryInterface;

class Reservation
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELED = 'canceled';

    public function __construct(
        private ReservationDiscount $reservationDiscount,
        private ReservationRepositoryInterface $reservationRepository,
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
        $reservation->price = $totalPrice;
        $reservation->save();
        $reservation->seats()->attach($seats);

        return $reservation;
    }

    public function cancel(int $reservationId): void
    {
        $reservation = $this->reservationRepository->getReservation($reservationId, [
            self::STATUS_APPROVED,
            self::STATUS_PENDING,
        ]);

        if (is_null($reservation)) {
            throw new NotFoundException('Reservation not found');
        }

        $lastStatus = $reservation->status;
        $reservation->status = self::STATUS_CANCELED;
        $reservation->save();

        if ($lastStatus == self::STATUS_APPROVED) {
            // fire event to rollback the payment
        } else {
            // fire event for pending reservation
        }
    }
}
