<?php

declare(strict_types=1);

namespace SnapAuth\Exception;

use RuntimeException;
use SnapAuth\ApiError;

class CodedError extends RuntimeException implements ApiError
{
    public function __construct(string $message, string $code)
    {
    }
}
