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
        Schema::create('seat', function (Blueprint $table) {
            $table->increments('seat_id');
            $table->integer('bus_id')->unsigned();
            $table->string('seat_number');
            $table->timestamps();

            $table->foreign('bus_id')->references('bus_id')->on('bus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat');
    }
};
