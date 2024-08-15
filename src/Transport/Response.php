<?php

declare(strict_types=1);

namespace SnapAuth\Transport;

final class Response
{
    /**
     * @param mixed[] $decodedResponse
     */
    public function __construct(
        public readonly int $code,
        public readonly array $decoded,
    ) {
    }
}
