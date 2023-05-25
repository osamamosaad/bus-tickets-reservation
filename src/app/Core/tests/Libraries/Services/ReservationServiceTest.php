<?php

include_once __DIR__ . '/ReservationServiceTestHelper.php';

use App\Core\Infrastructure\Exceptions\BusDepartedException;
use App\Core\Infrastructure\Exceptions\NotAvilableSeatException;
use App\Core\Infrastructure\Exceptions\NotReservableSeatException;
use App\Core\Infrastructure\Models\Reservation as ReservationModel;
use App\Core\Libraries\Bus\Repositories\ScheduleRepositoryInterface;
use App\Core\Libraries\Bus\Repositories\SeatRepositoryInterface;
use App\Core\Libraries\Common\DatabaseManagerInterface;
use App\Core\Libraries\Common\RequestInterface;
use App\Core\Libraries\Passenger\Passenger;
use App\Core\Libraries\Reservation\Repositories\ReservationRepositoryInterface;
use App\Core\Libraries\Reservation\Reservation;
use App\Core\Libraries\Services\ReservationService;
use Mockery;
use PHPUnit\Framework\TestCase;

class ReservationServiceTest extends TestCase
{
    use ReservationServiceTestHelper;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testRollbackCalledOnceAfterBeginTransactionException()
    {
        $scheduleRepository = Mockery::mock(ScheduleRepositoryInterface::class);
        $scheduleRepository->shouldReceive('getUpcomingSchedule')->andReturn(null);

        $databaseManager = Mockery::mock(DatabaseManagerInterface::class);
        $databaseManager->shouldReceive('beginTransaction')->once()->ordered();
        $databaseManager->shouldReceive('commit');
        $databaseManager->shouldReceive('rollback')->once()->ordered();

        $this->expectException(BusDepartedException::class);
        $service = new ReservationService(
            $databaseManager,
            $this->getMockPassengerLib(),
            $this->getMockReservationLib(),
            $scheduleRepository,
            $this->getMockReservationRepository(),
            $this->getMockSeatRepository(),
        );

        $service->execute([
            'passenger' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'scheduleId' => 123,
            'seats' => ['A1', 'A2'],
        ]);
    }

    public function testExecuteThrowsBusDepartedException()
    {
        $scheduleRepository = Mockery::mock(ScheduleRepositoryInterface::class);
        $scheduleRepository->shouldReceive('getUpcomingSchedule')->andReturn(null);

        $this->expectException(BusDepartedException::class);

        $service = new ReservationService(
            $this->getMockDatabaseManager(),
            $this->getMockPassengerLib(),
            $this->getMockReservationLib(),
            $scheduleRepository,
            $this->getMockReservationRepository(),
            $this->getMockSeatRepository(),
        );

        $service->execute([
            'passenger' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'scheduleId' => 123,
            'seats' => ['A1', 'A2'],
        ]);
    }

    public function testExecuteThrowsNotAvilableSeatException()
    {
        $seatRepository = Mockery::mock(SeatRepositoryInterface::class);
        $busId = 1;
        $seatRepository->shouldReceive('getBusSeatIdsBySeatNums')
            ->withArgs([['A1', 'A2'], $busId])
            ->andReturn([]);

        $activeReservation = [
            ['seat_number' => 'A3'],
            ['seat_number' => 'A4'],
        ];

        $reservationRepository = Mockery::mock(ReservationRepositoryInterface::class);
        $reservationRepository->shouldReceive('getReservedSeats')->andReturn($activeReservation);

        $service = new ReservationService(
            $this->getMockDatabaseManager(),
            $this->getMockPassengerLib(),
            $this->getMockReservationLib(),
            $this->getMockScheduleRepository(),
            $reservationRepository,
            $seatRepository,
        );

        $this->expectException(NotAvilableSeatException::class);

        $service->execute([
            'passenger' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'scheduleId' => 123,
            'seats' => ['A1', 'A2'],
        ]);
    }

    public function testExecuteThrowsNotReservableSeatException()
    {
        $schedule = (object) [
            'id' => 123,
            'bus' => (object) [
                'capacity' => 10,
            ],
            'route' => (object) [
                'id' => 456,
            ],
        ];

        $scheduleRepository = Mockery::mock(ScheduleRepositoryInterface::class);
        $scheduleRepository->shouldReceive('getUpcomingSchedule')->andReturn($schedule);

        $activeReservation = [
            ['seat_number' => 'A1'],
            ['seat_number' => 'A2'],
        ];

        $reservationRepository = Mockery::mock(ReservationRepositoryInterface::class);
        $reservationRepository->shouldReceive('getReservedSeats')->andReturn($activeReservation);

        $service = new ReservationService(
            $this->getMockDatabaseManager(),
            $this->getMockPassengerLib(),
            $this->getMockReservationLib(),
            $scheduleRepository,
            $reservationRepository,
            $this->getMockSeatRepository()
        );

        $this->expectException(NotReservableSeatException::class);

        $service->execute([
            'passenger' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'scheduleId' => 123,
            'seats' => ['A1', 'A2'],
        ]);
    }

    public function testExecuteReturnsReservation()
    {
        $schedule = (object) [
            'id' => 123,
            'bus' => (object) [
                'id' => 1,
                'capacity' => 10,
            ],
            'route' => (object) [
                'id' => 456,
            ],
            'price' => 100,
        ];

        $scheduleRepository = Mockery::mock(ScheduleRepositoryInterface::class);
        $scheduleRepository->shouldReceive('getUpcomingSchedule')->andReturn($schedule);

        $activeReservation = [
            ['seat_number' => 'A3'],
            ['seat_number' => 'A4'],
        ];

        $reservationRepository = Mockery::mock(ReservationRepositoryInterface::class);
        $reservationRepository->shouldReceive('getReservedSeats')->andReturn($activeReservation);

        $request = Mockery::mock(RequestInterface::class);
        $databaseManager = Mockery::mock(DatabaseManagerInterface::class);
        $databaseManager->shouldReceive('beginTransaction');
        $databaseManager->shouldReceive('commit');

        $passengerlib = Mockery::mock(Passenger::class);
        $passengerlib->shouldReceive('create')->andReturn((object) ['id' => 789]);

        $reservationlib = Mockery::mock(Reservation::class);
        $reservationlib->shouldReceive('reserve')->andReturn(new ReservationModel());

        $seatRepository = Mockery::mock(SeatRepositoryInterface::class);
        $seatRepository->shouldReceive('getBusSeatIdsBySeatNums')->andReturn([1, 2]);

        $service = new ReservationService(
            $this->getMockDatabaseManager(),
            $this->getMockPassengerLib(),
            $reservationlib,
            $scheduleRepository,
            $reservationRepository,
            $seatRepository
        );

        $reservation = $service->execute([
            'passenger' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'scheduleId' => 123,
            'seats' => ['A1', 'A2'],
            'status' => Reservation::STATUS_APPROVED,
        ]);

        $this->assertInstanceOf(ReservationModel::class, $reservation);
    }

    public function testStartTransactionAndCommitCalledOrdered()
    {
        $schedule = (object) [
            'id' => 123,
            'bus' => (object) [
                'id' => 1,
                'capacity' => 10,
            ],
            'route' => (object) [
                'id' => 456,
            ],
            'price' => 100,
        ];

        $scheduleRepository = Mockery::mock(ScheduleRepositoryInterface::class);
        $scheduleRepository->shouldReceive('getUpcomingSchedule')->andReturn($schedule);

        $reservationRepository = Mockery::mock(ReservationRepositoryInterface::class);
        $reservationRepository->shouldReceive('getReservedSeats');

        $passengerlib = Mockery::mock(Passenger::class);
        $passengerlib->shouldReceive('create')->andReturn((object) ['id' => 789]);

        $reservationlib = Mockery::mock(Reservation::class);
        $reservationlib->shouldReceive('reserve');

        $seatRepository = Mockery::mock(SeatRepositoryInterface::class);
        $seatRepository->shouldReceive('getBusSeatIdsBySeatNums');

        $databaseManager = Mockery::mock(DatabaseManagerInterface::class);
        $databaseManager->shouldReceive('beginTransaction')->once()->ordered();
        $databaseManager->shouldReceive('commit')->once()->ordered();
        $databaseManager->shouldReceive('rollback');

        $service = new ReservationService(
            $databaseManager,
            $this->getMockPassengerLib(),
            $reservationlib,
            $scheduleRepository,
            $reservationRepository,
            $seatRepository
        );

        $reservation = $service->execute([
            'passenger' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'scheduleId' => 123,
            'seats' => ['A1', 'A2'],
            'status' => Reservation::STATUS_APPROVED,
        ]);

        $this->assertInstanceOf(ReservationModel::class, $reservation);
    }
}
