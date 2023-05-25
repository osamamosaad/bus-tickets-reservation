<?php

namespace App\Core\Libraries\Passenger\Repositories;

use App\Core\Infrastructure\Models\Passenger;

interface PassengerRepositoryInterface
{
    public function findByEmail(string $email): ?Passenger;
}
