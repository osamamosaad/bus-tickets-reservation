<?php

namespace App\Core\Libraries\Common;

interface DatabaseManagerInterface
{
    public function beginTransaction(): void;

    public function commit(): void;

    public function rollback(): void;
}
