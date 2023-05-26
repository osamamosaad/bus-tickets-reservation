<?php

use App\Core\Infrastructure\Models\Discount;
use App\Core\Libraries\Reservation\DiscountTypes\NoDiscount;
use App\Core\Libraries\Reservation\DiscountTypes\RouteDiscount;
use App\Core\Libraries\Reservation\DiscountTypes\SeatDiscount;
use App\Core\Libraries\Reservation\Repositories\DiscountRepositoryInterface;
use App\Core\Libraries\Reservation\ReservationPriceCalculator;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class ReservationPriceCalculatorTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testCalcShouldReturnExpectedPriceWithSeatDiscount()
    {
        $tripInfo = [
            'route' => 'ABC',
            'seats' => ['A1', 'A2', 'A3'],
            'price' => 50
        ];

        $discount = new Discount();
        $discount->type = ReservationPriceCalculator::DISCOUNT_TYPE_SEAT;
        $discount->value = 2;
        $discount->discount_percentage = 10;

        $discountRepository = Mockery::mock(DiscountRepositoryInterface::class);
        $discountRepository->shouldReceive('getActiveBigDiscount')->andReturn($discount);

        $calculator = new ReservationPriceCalculator($discountRepository);
        $totalPrice = 150;
        $expectedPrice = $totalPrice * ($discount->discount_percentage / 100); // Assuming base price is 150
        $expectedPrice = $totalPrice - $expectedPrice;
        $this->assertEquals($expectedPrice, $calculator->calc($tripInfo));
    }

    public function testCalcShouldReturnExpectedPriceWithNoDiscount()
    {
        $tripInfo = [
            'route' => 'DEF',
            'seats' => ['C1'],
            'price' => 60,
        ];

        $discountRepository = Mockery::mock(DiscountRepositoryInterface::class);
        $discountRepository->shouldReceive('getActiveBigDiscount')->andReturn(null);

        $calculator = new ReservationPriceCalculator($discountRepository);
        $expectedPrice = 60; // Assuming base price is 60
        $this->assertEquals($expectedPrice, $calculator->calc($tripInfo));
    }
}
