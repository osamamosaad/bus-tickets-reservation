<?php

namespace App\Core\Infrastructure\Adapters;

use App\Core\Libraries\Common\DatabaseManagerInterface;
use Illuminate\Support\Facades\DB;

class DatabasManager implements DatabaseManagerInterface
{
    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }
}
