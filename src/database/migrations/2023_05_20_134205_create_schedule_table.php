<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bus_id')->unsigned();
            $table->integer('route_id')->unsigned();
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->foreign('bus_id')->references('id')->on('bus');
            $table->foreign('route_id')->references('id')->on('route');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule');
    }
};
