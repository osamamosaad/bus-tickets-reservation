<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservation', function (Blueprint $table) {
            $table->increments('reservation_id');
            $table->integer('passenger_id')->unsigned();
            $table->integer('schedule_id')->unsigned();
            $table->integer('seat_id')->unsigned();
            $table->timestamps();

            $table->foreign('passenger_id')->references('passenger_id')->on('passenger');
            $table->foreign('schedule_id')->references('schedule_id')->on('schedule');
            $table->foreign('seat_id')->references('seat_id')->on('seat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation');
    }
};
