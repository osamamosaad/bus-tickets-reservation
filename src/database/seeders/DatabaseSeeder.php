<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(BusSeeder::class);
        $this->call(RouteSeeder::class);
        $this->call(ScheduleSeeder::class);
        $this->call(PassengerSeeder::class);
        $this->call(ReservationSeeder::class);
    }
}
