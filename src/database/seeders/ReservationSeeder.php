<?php

namespace Database\Seeders;

use App\Core\Infrastructure\Models\Reservation;
use App\Core\Infrastructure\Models\Schedule;
use App\Core\Infrastructure\Models\Seat;
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
                'seats' => ['A1', 'A2'],
                'price' => 100,
                'status' => Reservation::STATUS_CANCELED,
            ],
            [
                'passenger_id' => 2,
                'schedule_id' => 1,
                'price' => 100,
                'seats' => ['A3'],
                'status' => Reservation::STATUS_APPROVED,
            ],
            [
                'passenger_id' => 3,
                'schedule_id' => 1,
                'price' => 100,
                'seats' => ['A4', 'A5'],
                'status' => Reservation::STATUS_APPROVED,
            ],

            [
                'passenger_id' => 4,
                'schedule_id' => 2,
                'price' => 150,
                'seats' => ['A1'],
                'status' => Reservation::STATUS_REJECTED,
            ],
            [
                'passenger_id' => 7,
                'schedule_id' => 2,
                'price' => 150,
                'seats' => ['B1', 'B2', 'B3'],
                'status' => Reservation::STATUS_APPROVED,
            ],
        ];

        $schedules = Schedule::all(['id', 'bus_id'])->keyBy('id')->toArray();

        foreach ($passengersWithSeats as $passengerWithSeats) {
            $reservation = Reservation::create([
                'passenger_id' => $passengerWithSeats['passenger_id'],
                'schedule_id' => $passengerWithSeats['schedule_id'],
                'price' => $passengerWithSeats['price'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'status' => $passengerWithSeats['status'],
            ]);

            foreach ($passengerWithSeats['seats'] as $seatNumber) {
                $seat = Seat::where('bus_id', $schedules[$passengerWithSeats['schedule_id']]['bus_id'])
                    ->where('seat_number', $seatNumber)
                    ->first();

                $reservation->seats()->attach($seat->id);
            }
        }
    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Reservation::truncate();
        DB::table('reservation_seat')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
