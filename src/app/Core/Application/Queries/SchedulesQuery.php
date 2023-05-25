<?php

namespace App\Core\Application\Queries;

use App\Core\Infrastructure\Exceptions\NotFoundException;
use App\Core\Libraries\Bus\Repositories\ScheduleRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SchedulesQuery
{
    public function __construct(
        private ScheduleRepositoryInterface $scheduleRepository
    ) {
    }

    public function listAll()
    {
        return $this->scheduleRepository->list();
    }

    public function getOne(int $id)
    {
        if ($schedule = $this->scheduleRepository->getOne($id)) {
            return $schedule;
        }

        throw new NotFoundException("No trip found");
    }
}
