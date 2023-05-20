<?php

namespace Database\Seeders;

use App\Models\Passenger;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassengerSeeder extends Seeder
{

    public function run()
    {
        $this->truncate();

        // Create 10 passangers
        for ($i = 1; $i <= 10; $i++) {
            Passenger::create([
                'name' => fake()->name,
                'email' => fake()->unique()->safeEmail(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Passenger::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
