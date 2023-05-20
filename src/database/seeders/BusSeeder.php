<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class BusSeeder extends Seeder
{

    public function run()
    {
        $this->truncate();

        $buses = [
            [
                'bus_number' => 'ABC123',
                'capacity' => 20,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'bus_number' => 'XYZ456',
                'capacity' => 20,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($buses as $busData) {
            Bus::create($busData);
        }

        $bus1 = Bus::find(1);
        $bus2 = Bus::find(2);


        for ($i = 1; $i <= 10; $i++) {
            $bus1->seats()->create([
                'seat_number' => 'A' . $i,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $bus1->seats()->create([
                'seat_number' => 'B' . $i,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }


        for ($i = 1; $i <= $bus2->capacity; $i++) {
            $bus2->seats()->create([
                'seat_number' => 'A' . $i,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $bus2->seats()->create([
                'seat_number' => 'B' . $i,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Bus::truncate();
        Seat::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
