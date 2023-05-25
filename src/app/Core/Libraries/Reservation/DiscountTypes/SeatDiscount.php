<?php

namespace App\Core\Libraries\Reservation\DiscountTypes;

class SeatDiscount extends DiscountCalculatorTemplate
{
    protected function isEligible($tripInfo, $discountInfo): bool
    {
        return count($tripInfo['seats']) > $discountInfo->value;
    }
}
