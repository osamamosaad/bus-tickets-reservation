<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'type' => 'Reservation',
            'id' => $this->id,
            'attributes' => [
                'routeName' => $this->schedule->route->name,
                'seatNum' => $this->seat->seat_number,
                'passenger' => [
                    "name" => $this->passenger->name,
                    "email" => $this->passenger->email
                ],
                'status' => $this->status,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
        ];
    }
}
