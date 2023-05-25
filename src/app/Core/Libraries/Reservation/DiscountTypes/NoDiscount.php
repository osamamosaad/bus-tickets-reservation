<?php

namespace App\Core\Libraries\Reservation\DiscountTypes;

class NoDiscount extends DiscountCalculatorTemplate
{
    protected function isEligible($tripInfo, $discountInfo = null): bool
    {
        return false;
    }
}
