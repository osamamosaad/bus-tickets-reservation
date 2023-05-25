<?php

namespace App\Core\Infrastructure\Providers;

use App\Core\Infrastructure\Repositories\DiscountRepository;
use App\Core\Infrastructure\Repositories\PassengerRepository;
use App\Core\Infrastructure\Repositories\ReservationRepository;
use App\Core\Infrastructure\Repositories\ScheduleRepository;
use App\Core\Infrastructure\Repositories\SeatRepository;
use App\Core\Libraries\Bus\Repositories\ScheduleRepositoryInterface;
use App\Core\Libraries\Bus\Repositories\SeatRepositoryInterface;
use App\Core\Libraries\Passenger\Repositories\PassengerRepositoryInterface;
use App\Core\Libraries\Reservation\Repositories\DiscountRepositoryInterface;
use App\Core\Libraries\Reservation\Repositories\ReservationRepositoryInterface;
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

        $this->app->singleton(
            DiscountRepositoryInterface::class,
            DiscountRepository::class
        );
    }
}
