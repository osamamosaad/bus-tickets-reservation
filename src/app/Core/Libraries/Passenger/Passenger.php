<?php

namespace App\Core\Libraries\Passenger;

use App\Core\Infrastructure\Models\Passenger as PassengerModel;
use App\Core\Libraries\Passenger\Repositories\PassengerRepositoryInterface;

class Passenger
{
    public function __construct(
        private PassengerRepositoryInterface $passengerRepository
    ) {
    }

    public function create(string $name, string $email): PassengerModel
    {
        $passenger = $this->passengerRepository->findByEmail($email);
        if (! $passenger) {
            $passenger = new PassengerModel();
            $passenger->name = $name;
            $passenger->email = $email;
            $passenger->save();
        }

        return $passenger;
    }
}
