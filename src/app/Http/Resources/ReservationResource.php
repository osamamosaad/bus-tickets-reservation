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
                'seats' => array_column($this->seats->toArray(), 'seat_number'),
                'passenger' => [
                    'name' => $this->passenger->name,
                    'email' => $this->passenger->email,
                ],
                'price' => $this->price,
                'status' => $this->status,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
        ];
    }
}
