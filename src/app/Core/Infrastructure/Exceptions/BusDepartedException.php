<?php

namespace App\Core\Infrastructure\Exceptions;

use App\Exceptions\ValidationException;

class BusDepartedException extends ValidationException implements \Throwable
{
    public function __construct()
    {
        parent::__construct("The bus has already departed");
    }
}
