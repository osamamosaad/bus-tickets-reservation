<?php

namespace App\Core\Infrastructure\Repositories;

use App\Core\Infrastructure\Models\Discount;
use App\Core\Libraries\Reservation\Repositories\DiscountRepositoryInterface;

class DiscountRepository implements DiscountRepositoryInterface
{
    public function __construct(
        private Discount $discount
    ) {
    }

    public function getActiveBigDiscount(): ?Discount
    {
        return $this->discount
            ->where('expired_at', '>', now())
            ->orderBy('discount_percentage', 'desc')
            ->first();
    }
}
