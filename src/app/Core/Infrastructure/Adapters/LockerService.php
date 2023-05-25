<?php

namespace App\Core\Infrastructure\Adapters;

use Illuminate\Support\Facades\Redis;

class LockerService
{
    public function lock(string $key, $value, int $duration): bool
    {
        return Redis::set($key, $value, 'EX', $duration, 'NX');
    }

    public function getVal(string $key)
    {
        return Redis::get($key);
    }

    public function release(string $key): void
    {
        Redis::del($key);
    }
}
