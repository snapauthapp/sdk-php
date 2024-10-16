<?php

declare(strict_types=1);

namespace SnapAuth\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(Network::class)]
#[Small]
class NetworkTest extends TestCase
{
    public function testConstruct(): void
    {
        $code = CURLE_OPERATION_TIMEDOUT;
        $e = new Network($code);
        // @phpstan-ignore argument.type (curl_strerror will not return null here)
        self::assertStringContainsString(curl_strerror($code), $e->getMessage());
    }
}
