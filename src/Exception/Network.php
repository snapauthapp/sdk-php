<?php

declare(strict_types=1);

namespace SnapAuth\Exception;

use RuntimeException;
use SnapAuth\ApiError;

/**
 * A network interruption occurred.
 */
class Network extends RuntimeException implements ApiError
{
    /**
     * @param $code a cURL error code
     *
     * @internal Constructing errors is not covered by BC
     */
    public function __construct(int $code)
    {
        $message = curl_strerror($code);
        parent::__construct(
            message: 'SnapAuth network error: ' . $message,
            code: $code,
        );
    }
}
