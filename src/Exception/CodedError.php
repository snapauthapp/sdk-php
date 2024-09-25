<?php

declare(strict_types=1);

namespace SnapAuth\Exception;

use RuntimeException;
use SnapAuth\{
    ApiError,
    ErrorCode,
};

/**
 * The API returned a well-formed coded error message. Examine the $errorCode
 * property for additional information.
 */
class CodedError extends RuntimeException implements ApiError
{
    public readonly ErrorCode $errorCode;

    /**
     * @param int $httpCode The HTTP status code of the error response
     *
     * @internal Constructing errors is not covered by BC
     */
    public function __construct(string $message, string $errorCode, int $httpCode)
    {
        parent::__construct(message: "[$errorCode] $message", code: $httpCode);
        $this->errorCode = ErrorCode::tryFrom($errorCode) ?? ErrorCode::Unknown;
    }
}
