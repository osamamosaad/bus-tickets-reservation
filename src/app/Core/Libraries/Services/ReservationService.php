<?php

namespace App\Core\Libraries\Services;

use App\Core\Infrastructure\Exceptions\BusDepartedException;
use App\Core\Infrastructure\Exceptions\NotAvilableSeatException;
use App\Core\Infrastructure\Exceptions\NotReservableSeatException;
use App\Core\Libraries\Bus\Repositories\ScheduleRepositoryInterface;
use App\Core\Libraries\Bus\Repositories\SeatRepositoryInterface;
use App\Core\Libraries\Common\DatabaseManagerInterface;
use App\Core\Libraries\Passenger\Passenger;
use App\Core\Libraries\Reservation\Repositories\ReservationRepositoryInterface;
use App\Core\Libraries\Reservation\Reservation;

class ReservationService
{
    public function __construct(
        private DatabaseManagerInterface $databaseManager,
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
            $this->databaseManager->beginTransaction();

            // check if the schedule is not missed
            $schedule = $this->scheduleRepository->getUpcomingSchedule($data['scheduleId']);

            if (! $schedule) {
                throw new BusDepartedException();
            }

            // Filter the seats from duplicate
            $requestedSeats = array_unique($data['seats']);
            $activeReservation = $this->reservationRepository->getReservedSeats($data['scheduleId']);

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

            $this->databaseManager->commit();

            return $reservation;
        } catch (\Exception $th) {
            $this->databaseManager->rollback();

            throw $th;
        }
    }

    public function checkSeatsAvilabelity($schedule, array $activeReservation, array $requestedSeats): bool
    {
        $seatsNumber = array_column($activeReservation, 'seat_number');

        $avilabelSeats = $schedule->bus->capacity - count($seatsNumber);

        if ($avilabelSeats >= count($requestedSeats)) {
            return true;
        }

        throw new NotAvilableSeatException($avilabelSeats, count($requestedSeats));
    }

    public function isReservable(array $activeReservation, $requestedSeats): bool
    {
        $seatsNumber = array_column($activeReservation, 'seat_number');

        $seatsIntersection = array_intersect($seatsNumber, $requestedSeats);

        if (! empty($seatsIntersection)) {
            throw new NotReservableSeatException($seatsIntersection);
        }

        return true;
    }
}
