<?php

namespace App\Core\Application\Commands;

use App\Core\Infrastructure\{
    Adapters\LockerService,
    Exceptions\BusReservationBusyException,
    Models\Reservation as ReservationModel,
};
use App\Core\Libraries\{
    Common\RequestInterface,
    Reservation\Reservation,
    Services\ReservationService,
};

class CreateReservationCommand
{
    const LOCK_DURATION = 60 * 2; // Lock duration in seconds

    public function __construct(
        private RequestInterface $request,
        private LockerService $lockerService,
        private ReservationService $reservationService,
    ) {
    }

    public function execute(): ReservationModel
    {
        $data = $this->request->getBodyRequest();

        $lockKey = 'lock:schedule:' . $data['scheduleId'];

        $acquired = $this->lockerService->lock($lockKey, $data['passenger']['email'], self::LOCK_DURATION);

        if ($acquired === true || $this->lockerService->getVal($lockKey) === $data['passenger']['email']) {

            $reservation = $this->reservationService->execute($data);
            /**
             * Only release the lock if the reservation is approved,
             * to give the passanger chance if there is someting wrong happened
             */
            if ($reservation->status === Reservation::STATUS_APPROVED) {
                $this->lockerService->release($lockKey);
            }

            return $reservation;
        }

        throw new BusReservationBusyException();
    }
}
