<?php

namespace App\Core\Application\Commands;

use Illuminate\Support\Facades\DB;
use App\Core\Infrastructure\{
    Exceptions\BusDepartedException,
    Exceptions\NotAvilableSeatException,
    Exceptions\NotReservableSeatException,
};
use App\Core\Libraries\{
    Bus\Repositories\ScheduleRepositoryInterface,
    Common\RequestInterface,
    Passenger\Passenger,
    Reservation\Repositories\ReservationRepositoryInterface,
    Reservation\Reservation,
};

class CreateReservationCommand
{
    public function __construct(
        private RequestInterface $request,
        private Passenger $passengerlib,
        private Reservation $reservationlib,
        private ScheduleRepositoryInterface $scheduleRepository,
        private ReservationRepositoryInterface $reservationRepository,
    ) {
    }

    public function execute()
    {
        $data = $this->request->getBodyRequest();
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
                array_column($activeReservation, "id")
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
