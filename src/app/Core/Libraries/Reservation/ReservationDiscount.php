<?php

namespace App\Core\Libraries\Reservation;

use App\Core\Libraries\Reservation\DiscountTypes\NoDiscount;
use App\Core\Libraries\Reservation\DiscountTypes\RouteDiscount;
use App\Core\Libraries\Reservation\DiscountTypes\SeatDiscount;
use App\Core\Libraries\Reservation\Repositories\DiscountRepositoryInterface;

class ReservationDiscount
{
    public const DISCOUNT_TYPE_SEAT = 'seat';

    public const DISCOUNT_TYPE_ROUTE = 'route';

    public function __construct(
        private DiscountRepositoryInterface $discountRepository
    ) {
    }

    public function calc($tripInfo): float
    {
        $discount = $this->discountRepository->getActiveBigDiscount();

        switch ($discount?->type) {
            case self::DISCOUNT_TYPE_SEAT:
                $discountCalculator = new SeatDiscount();

                break;
            case self::DISCOUNT_TYPE_ROUTE:
                $discountCalculator = new RouteDiscount();

                break;
            default:
                $discountCalculator = new NoDiscount();

                break;
        }

        return $discountCalculator->calc($tripInfo, $discount);
    }
}
