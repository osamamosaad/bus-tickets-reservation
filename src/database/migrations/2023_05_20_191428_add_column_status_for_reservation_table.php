<?php

use App\Core\Infrastructure\Models\Reservation;
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
        // Add column status to reservation table after seat_id column
        Schema::table('reservation', function (Blueprint $table) {
            $table->enum('status', [
                Reservation::STATUS_PENDING,
                Reservation::STATUS_APPROVED,
                Reservation::STATUS_REJECTED,
                Reservation::STATUS_CANCELED,
            ])->default(Reservation::STATUS_PENDING)
            ->after('schedule_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
