<?php

namespace App\Core\Infrastructure\Providers;

use App\Core\Infrastructure\{
    Repositories\PassengerRepository,
    Repositories\ReservationRepository,
    Repositories\ScheduleRepository,
    Repositories\SeatRepository,
};
use App\Core\Libraries\{
    Bus\Repositories\ScheduleRepositoryInterface,
    Bus\Repositories\SeatRepositoryInterface,
    Passenger\Repositories\PassengerRepositoryInterface,
    Reservation\Repositories\ReservationRepositoryInterface,
};
use Illuminate\Support\ServiceProvider;

class RepositoriesProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            ScheduleRepositoryInterface::class,
            ScheduleRepository::class
        );

        $this->app->singleton(
            ReservationRepositoryInterface::class,
            ReservationRepository::class
        );

        $this->app->singleton(
            PassengerRepositoryInterface::class,
            PassengerRepository::class
        );

        $this->app->singleton(
            SeatRepositoryInterface::class,
            SeatRepository::class
        );
    }
}
