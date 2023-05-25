<?php

namespace App\Core\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $table = 'bus';

    // Create relationship between Bus and Seat
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
