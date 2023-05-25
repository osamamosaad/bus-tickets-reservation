<?php

namespace App\Core\Libraries\Services;

use Illuminate\Support\Facades\DB;
use App\Core\Infrastructure\{
    Exceptions\BusDepartedException,
    Exceptions\NotAvilableSeatException,
    Exceptions\NotReservableSeatException,
};
use App\Core\Libraries\{
    Passenger\Passenger,
    Common\RequestInterface,
    Bus\Repositories\ScheduleRepositoryInterface,
    Bus\Repositories\SeatRepositoryInterface,
    Reservation\Reservation,
    Reservation\Repositories\ReservationRepositoryInterface,
};

class ReservationService
{
    public function __construct(
        private RequestInterface $request,
        private Passenger $passengerlib,
        private Reservation $reservationlib,
        private ScheduleRepositoryInterface $scheduleRepository,
        private ReservationRepositoryInterface $reservationRepository,
        private SeatRepositoryInterface $seatRepository,
    ) {
    }

    public function execute(array $data)
    {
        $passenger = $this->passengerlib->create(
            $data['passenger']['name'],
            $data['passenger']['email']
        );

        try {
            DB::beginTransaction();

            // check if the schedule is not missed
            $schedule = $this->scheduleRepository->getUpcomingSchedule($data["scheduleId"]);
            if (!$schedule) {
                throw new BusDepartedException();
            }

            // Filter the seats from duplicate
            $requestedSeats = array_unique($data["seats"]);
            $activeReservation = $this->reservationRepository->getReservedSeats($data["scheduleId"]);

            $this->checkSeatsAvilabelity($schedule, $activeReservation, $requestedSeats);
            $this->isReservable($activeReservation, $requestedSeats);

            // Create the reservation and set the seats
            $reservation = $this->reservationlib->reserve(
                    $passenger->id,
                    $schedule->id,
                    $schedule->route->id,
                    $this->seatRepository->getBusSeatIdsBySeatNums($requestedSeats, $schedule->bus->id),
                    $schedule->price,
                    $data['status'] ?? Reservation::STATUS_APPROVED,
                );

            DB::commit();
            return $reservation;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function checkSeatsAvilabelity($schedule, array $activeReservation, array $requestedSeats): bool
    {
        $seatsNumber = array_column($activeReservation, "seat_number");

        $avilabelSeats = $schedule->bus->capacity - count($seatsNumber);

        if ($avilabelSeats >= count($requestedSeats)) {
            return true;
        }

        throw new NotAvilableSeatException($avilabelSeats, count($requestedSeats));
    }

    public function isReservable(array $activeReservation, $requestedSeats): bool
    {
        $seatsNumber = array_column($activeReservation, "seat_number");

        $seatsIntersection = array_intersect($seatsNumber, $requestedSeats);

        if (!empty($seatsIntersection)) {
            throw new NotReservableSeatException($seatsIntersection);
        }

        return true;
    }
}
