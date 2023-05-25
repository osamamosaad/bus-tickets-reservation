<?php

namespace App\Core\Libraries\Bus\Repositories;

use App\Core\Infrastructure\Models\Schedule;
use Illuminate\Support\Collection;

interface ScheduleRepositoryInterface
{
    public function getUpcomingSchedule(int $scheduleId);

    public function list(): Collection;

    public function getOne(int $id): ?Schedule;
}
