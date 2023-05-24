<?php

namespace App\Core\Infrastructure\Exceptions;

class BusDepartedException extends \Exception implements \Throwable
{
    public function __construct()
    {
        parent::__construct("The bus has already departed", 0);
    }
}
