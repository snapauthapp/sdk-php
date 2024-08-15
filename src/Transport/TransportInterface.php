<?php

declare(strict_types=1);

namespace SnapAuth\Transport;

interface TransportInterface
{
    /**
     * @internal This method is made public for cases where APIs do not have
     * native SDK support, but is NOT considered part of the public, stable
     * API and is not subject to SemVer.
     *
     * @param mixed[] $params
     */
    public function makeApiCall(string $route, array $params): Response;
}
