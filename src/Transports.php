<?php

declare(strict_types=1);

namespace SnapAuth;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\{RequestFactoryInterface, StreamFactoryInterface};

class Transports
{
    public function __construct(
        public readonly ClientInterface $client,
        public readonly RequestFactoryInterface $requestFactory,
        public readonly StreamFactoryInterface $streamFactory,
    ) {
    }
}
