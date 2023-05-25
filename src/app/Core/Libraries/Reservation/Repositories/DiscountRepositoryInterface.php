<?php

namespace App\Core\Libraries\Reservation\Repositories;

use App\Core\Infrastructure\Models\Discount;

interface DiscountRepositoryInterface
{
    public function getActiveBigDiscount(): ?Discount;
}
