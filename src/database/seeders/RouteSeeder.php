<?php

namespace Database\Seeders;

use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouteSeeder extends Seeder
{

    public function run()
    {
        $this->truncate();

        $routes = [
            [
                'name' => 'Cairo - Alex',
                'source' => 'Cairo',
                'destination' => 'Alex',
                'distance' => 90,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Cairo - Aswan',
                'source' => 'Cairo',
                'destination' => 'Aswan',
                'distance' => 150,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($routes as $routeData) {
            Route::create($routeData);
        }
    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Route::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
