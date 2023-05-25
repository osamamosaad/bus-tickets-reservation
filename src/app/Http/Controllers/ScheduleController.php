<?php

namespace App\Http\Controllers;

use App\Core\Application\Queries\SchedulesQuery;
use App\Http\Resources\ScheduleResource;

class ScheduleController extends Controller
{
    public function list(SchedulesQuery $scheduleQuery)
    {
        return ScheduleResource::collection($scheduleQuery->listAll());
    }

    public function get(int $id, SchedulesQuery $scheduleQuery)
    {
        return new ScheduleResource($scheduleQuery->getOne($id));
    }

    public function create()
    {
        // create schedule
    }
}
