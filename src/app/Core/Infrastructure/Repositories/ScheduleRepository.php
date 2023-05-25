<?php

namespace App\Core\Infrastructure\Repositories;

use App\Core\Infrastructure\Models\Schedule;
use App\Core\Libraries\Bus\Repositories\ScheduleRepositoryInterface;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function __construct(
        private Schedule $scheduleModel
    ) {
    }

    public function list()
    {
        return $this->scheduleModel->with(['route', 'bus'])->get();
    }

    public function getOne(int $id): ?Schedule
    {
        return $this->scheduleModel->with(['route', 'bus'])->find($id);
    }

    public function getUpcomingSchedule(int $scheduleId): ?Schedule
    {
        return $this->scheduleModel->where('id', $scheduleId)->where('departure_time', '>', now())->first();
    }
}
