<?php

namespace App\Core\Libraries\Reservation\DiscountTypes;

abstract class DiscountCalculatorTemplate
{
    abstract protected function isEligible($tripInfo, $discountInfo): bool;

    public function calc($tripInfo, $discountInfo): float
    {
        if (!$this->isEligible($tripInfo, $discountInfo)) {
            return $tripInfo["price"];
        }

        $discountAmount =  $tripInfo["price"] * ($discountInfo->discount_percentage / 100);
        return $tripInfo["price"] - $discountAmount;
    }
}
