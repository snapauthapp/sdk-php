<?php

declare(strict_types=1);

namespace SnapAuth\Exception;

use RuntimeException;
use SnapAuth\ApiError;

use function sprintf;

/**
 * A response arrived, but was not in an expected format
 */
class MalformedResponse extends RuntimeException implements ApiError
{
    /**
     * @internal Constructing errors is not covered by BC
     */
    public function __construct(string $details, int $statusCode)
    {
        parent::__construct(
            message: sprintf(
                '[HTTP %d] SnapAuth API returned data in an unexpected format: %s',
                $statusCode,
                $details,
            ),
            code: $statusCode,
        );
    }
}
