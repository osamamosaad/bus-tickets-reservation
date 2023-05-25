<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'type' => 'Schedule',
            'id' => $this->id,
            'attributes' => [
                'bus' => [
                    'busNumber' => $this->bus->bus_number,
                    'capacity' => $this->bus->capacity,
                ],
                'routeName' => $this->route->name,
                'price' => $this->price,
                'departureTime' => $this->departure_time,
                'arrivalTime' => $this->arrival_time,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
        ];
    }
}
