<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{

    public function run()
    {
        $this->truncate();

        $passengersWithSeats = [
            [
                'passenger_id' => 1,
                'schedule_id' => 1,
                'seats' => ['A1', 'A2', 'B1', 'B2'],
                'status' => Reservation::STATUS_CANCELED,
            ],
            [
                'passenger_id' => 2,
                'schedule_id' => 1,
                'seats' => ['A3', 'A4', 'B5', 'B6'],
                'status' => Reservation::STATUS_APPROVED,
            ],
            [
                'passenger_id' => 3,
                'schedule_id' => 1,
                'seats' => ['A10', 'A9', 'B10'],
                'status' => Reservation::STATUS_APPROVED,
            ],

            [
                'passenger_id' => 4,
                'schedule_id' => 2,
                'seats' => ['A1'],
                'status' => Reservation::STATUS_REJECTED,
            ],

            [
                'passenger_id' => 5,
                'schedule_id' => 2,
                'seats' => ['A2', 'A3', 'A4', 'B1', 'B2', 'B3', 'B4'],
                'status' => Reservation::STATUS_APPROVED,
            ],
            [
                'passenger_id' => 7,
                'schedule_id' => 2,
                'seats' => ['B10', 'B9', 'B5', 'A10'],
                'status' => Reservation::STATUS_APPROVED,
            ],
        ];

        $schedules = Schedule::all(['id', 'bus_id'])->keyBy('id')->toArray();

        foreach ($passengersWithSeats as $passengerWithSeats) {
            foreach ($passengerWithSeats['seats'] as $seatNumber) {
                $seat = Seat::where('bus_id', $schedules[$passengerWithSeats['schedule_id']]['bus_id'])
                    ->where('seat_number', $seatNumber)
                    ->first();

                Reservation::create([
                    'passenger_id' => $passengerWithSeats['passenger_id'],
                    'schedule_id' => $passengerWithSeats['schedule_id'],
                    'seat_id' => $seat->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'status' => $passengerWithSeats['status'],
                ]);
            }
        }
    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Reservation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
