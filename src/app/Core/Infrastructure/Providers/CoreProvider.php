<?php

namespace App\Core\Infrastructure\Providers;

use App\Core\Infrastructure\Adapters\{Request, DatabasManager};
use App\Core\Libraries\Common\{RequestInterface, DatabaseManagerInterface};
use Illuminate\Support\ServiceProvider;

class CoreProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(RequestInterface::class, Request::class);
        $this->app->bind(DatabaseManagerInterface::class, DatabasManager::class);
    }
}
