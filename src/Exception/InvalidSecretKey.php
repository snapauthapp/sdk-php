<?php

declare(strict_types=1);

namespace SnapAuth\Exception;

use InvalidArgumentException;
use SnapAuth\ApiError;

class InvalidSecretKey extends InvalidArgumentException implements ApiError
{
    public function __construct()
    {
        parent::__construct(
            message: 'Invalid secret key. Please verify you copied the full value from the SnapAuth dashboard.',
        );
    }
}
