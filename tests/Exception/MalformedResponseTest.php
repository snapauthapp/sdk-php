<?php

declare(strict_types=1);

namespace SnapAuth\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(MalformedResponse::class)]
#[Small]
class MalformedResponseTest extends TestCase
{
    public function testConstruct(): void
    {
        $e = new MalformedResponse('Invalid data', 503);
        self::assertStringContainsString('Invalid data', $e->getMessage());
        self::assertSame(503, $e->getCode());
    }
}
