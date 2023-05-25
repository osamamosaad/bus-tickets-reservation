<?php

namespace App\Exceptions;

class ForbiddenException extends \Exception
{
    public function __construct(string $message = 'Not Authorized', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
