<?php

namespace Database\Seeders;

use App\Core\Infrastructure\Models\Discount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscountSeeder extends Seeder
{

    public function run()
    {
        $this->truncate();

        Discount::create([
            'type' => "seat",
            'value' => 5,
            'discount_percentage' => 10,
            'expired_at' => date_create()->modify('+30 day')
        ]);
    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Discount::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
