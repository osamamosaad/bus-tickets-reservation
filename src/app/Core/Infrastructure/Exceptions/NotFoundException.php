<?php

namespace App\Core\Infrastructure\Exceptions;

use App\Exceptions\NotFoundException as AppNotFoundException;

class NotFoundException extends AppNotFoundException implements \Throwable
{
}
