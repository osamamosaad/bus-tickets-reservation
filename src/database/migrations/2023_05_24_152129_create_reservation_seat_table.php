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
        // Create reservation_seat table with relation with seat table and reservation table
        Schema::create('reservation_seat', function (Blueprint $table) {
            $table->increments('id');

            // Add the reservation_id column
            $table->unsignedInteger('reservation_id');
            $table->index('reservation_id', 'reservation_seat-ix-reservation_id');
            $table->foreign('reservation_id')
                ->references('id')->on('reservation')
                ->constrained('reservation_seat-fk-reservation_id');

            // Add the seat_id column
            $table->unsignedInteger('seat_id');
            $table->index('seat_id', 'reservation_seat-ix-seat_id');
            $table->foreign('seat_id')
                ->references('id')->on('seat')
                ->constrained('reservation_seat-fk-seat_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_seat');
    }
};
