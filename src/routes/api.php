<?php

use Illuminate\Support\Facades\File;

// Load all routes from the ApiRoutes folder
$files = File::allFiles(__DIR__ . '/ApiRoutes');
foreach ($files as $file) {
    require_once $file->getPathname();
}
