<?php

declare(strict_types=1);

namespace SnapAuth\Exception;

use InvalidArgumentException;
use SnapAuth\ApiError;

class MissingSecretKey extends InvalidArgumentException implements ApiError
{
    public function __construct()
    {
        parent::__construct(
            'Secret key missing. It can be explictly provided, or it can be ' .
            'auto-detected from the SNAPAUTH_SECRET_KEY environment variable.'
        );
    }
}
