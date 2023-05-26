<?php

namespace Database\Seeders;

use App\Core\Infrastructure\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        $this->truncate();

        $schedules = [
            [
                'bus_id' => 1,
                'route_id' => 1,
                'departure_time' => date_create()->modify('+30 day'),
                'arrival_time' => date_create()->modify('+30 day'),
                'price' => 100,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'bus_id' => 2,
                'route_id' => 2,
                'departure_time' => date_create()->modify('+30 day'),
                'arrival_time' => date_create()->modify('+30 day'),
                'price' => 200,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($schedules as $scheduleData) {
            Schedule::create($scheduleData);
        }
    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schedule::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
