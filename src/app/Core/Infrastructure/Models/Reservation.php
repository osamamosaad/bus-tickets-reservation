<?php

namespace App\Core\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservation';

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_CANCELED = 'canceled';

    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class);
    }
}
