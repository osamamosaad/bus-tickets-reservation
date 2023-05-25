<?php

namespace App\Core\Libraries\Bus\Repositories;

interface ScheduleRepositoryInterface
{
    public function getUpcomingSchedule(int $scheduleId);
}
