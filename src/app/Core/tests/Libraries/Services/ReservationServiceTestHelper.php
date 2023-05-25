<?php

use App\Core\Infrastructure\Models\Passenger as PassengerModel;
use App\Core\Infrastructure\Models\Reservation as ReservationModel;
use App\Core\Libraries\Bus\Repositories\ScheduleRepositoryInterface;
use App\Core\Libraries\Bus\Repositories\SeatRepositoryInterface;
use App\Core\Libraries\Common\DatabaseManagerInterface;
use App\Core\Libraries\Passenger\Passenger;
use App\Core\Libraries\Reservation\Repositories\ReservationRepositoryInterface;
use App\Core\Libraries\Reservation\Reservation;

trait ReservationServiceTestHelper
{
    public function getMockPassengerLib()
    {
        $passengerMock = Mockery::mock(Passenger::class);
        $passengerMock->shouldReceive('create')->andReturn($this->getMockPassengerModel());

        return $passengerMock;
    }

    public function getMockDatabaseManager()
    {
        $databaseManager = Mockery::mock(DatabaseManagerInterface::class);
        $databaseManager->shouldReceive('beginTransaction');
        $databaseManager->shouldReceive('commit');
        $databaseManager->shouldReceive('rollback');

        return $databaseManager;
    }

    public function getMockReservationLib()
    {
        $reservationMock = Mockery::mock(Reservation::class);
        $reservationMock->shouldReceive('reserve')->andReturn($this->getMockReservationModel());

        return $reservationMock;
    }

    protected function getMockPassengerModel()
    {
        $passenger = new PassengerModel();
        $passenger->id = 1;

        return $passenger;
    }

    protected function getMockReservationModel()
    {
        $passenger = new ReservationModel();
        $passenger->id = 1;

        return $passenger;
    }

    public function getMockScheduleRepository()
    {
        $schedule = (object) [
            'id' => 123,
            'bus' => (object) [
                'id' => 1, // Provide a valid bus ID
                'capacity' => 1,
            ],
            'route' => (object) [
                'id' => 1, // Provide a valid rout ID
            ],
            'price' => 100,
        ];

        $scheduleRepository = Mockery::mock(ScheduleRepositoryInterface::class);
        $scheduleRepository->shouldReceive('getUpcomingSchedule')->andReturn($schedule);

        return $scheduleRepository;
    }

    public function getMockReservationRepository()
    {
        $reservationRepository = Mockery::mock(ReservationRepositoryInterface::class);

        return $reservationRepository;
    }

    public function getMockSeatRepository()
    {
        $seatRepository = Mockery::mock(SeatRepositoryInterface::class);

        return $seatRepository;
    }
}
