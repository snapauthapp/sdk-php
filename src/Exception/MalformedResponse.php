<?php

declare(strict_types=1);

namespace SnapAuth\Exception;

use RuntimeException;
use SnapAuth\ApiError;

/**
 * A response arrived, but was not in an expected format
 */
class MalformedResponse extends RuntimeException implements ApiError
{
    public function __construct(string $details)
    {
        parent::__construct(
            message: 'SnapAuth API returned data in an unexpected format: ' . $details,
        );
    }
}
