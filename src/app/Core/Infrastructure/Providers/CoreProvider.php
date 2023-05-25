<?php

namespace App\Core\Infrastructure\Providers;

use App\Core\Infrastructure\Adapters\DatabasManager;
use App\Core\Infrastructure\Adapters\Request;
use App\Core\Libraries\Common\DatabaseManagerInterface;
use App\Core\Libraries\Common\RequestInterface;
use Illuminate\Support\ServiceProvider;

class CoreProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(RequestInterface::class, Request::class);
        $this->app->bind(DatabaseManagerInterface::class, DatabasManager::class);
    }
}
