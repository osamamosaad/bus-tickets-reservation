<?php

namespace App\Core\Libraries\Reservation\DiscountTypes;

abstract class DiscountCalculatorTemplate
{
    abstract protected function isEligible($tripInfo, $discountInfo): bool;

    public function calc($tripInfo, $discountInfo): float
    {
        $totalPrice = $tripInfo["price"] * count($tripInfo['seats']);
        if (!$this->isEligible($tripInfo, $discountInfo)) {
            return $totalPrice;
        }

        $discountAmount =  $totalPrice * ($discountInfo->discount_percentage / 100);
        return $totalPrice - $discountAmount;
    }
}
