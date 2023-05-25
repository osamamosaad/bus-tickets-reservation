<?php

namespace App\Core\Infrastructure\Repositories;

use App\Core\Infrastructure\Models\Passenger;
use App\Core\Libraries\Passenger\Repositories\PassengerRepositoryInterface;

class PassengerRepository implements PassengerRepositoryInterface
{
    public function __construct(
        private Passenger $passengerModel
    ) {
    }

    public function findByEmail(string $email): ?Passenger
    {
        return $this->passengerModel->where('email', $email)->first();
    }
}
