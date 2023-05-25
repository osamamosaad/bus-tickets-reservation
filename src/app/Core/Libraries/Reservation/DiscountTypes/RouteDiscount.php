<?php

namespace App\Core\Libraries\Reservation\DiscountTypes;

class RouteDiscount extends DiscountCalculatorTemplate
{
    protected function isEligible($tripInfo, $discountInfo): bool
    {
        return $tripInfo['routeId'] == $discountInfo->value;
    }
}
