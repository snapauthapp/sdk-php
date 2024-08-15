<?php

declare(strict_types=1);

namespace SnapAuth\Transport;

/**
 * This is a vastly simplified version of PSR-7 ResponseInterface. It's used to
 * avoid forcing an external dependency on clients.
 */
final class Response
{
    /**
     * @param mixed[] $decoded
     */
    public function __construct(
        public readonly int $code,
        public readonly array $decoded,
    ) {
    }
}
