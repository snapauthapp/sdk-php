<?php

declare(strict_types=1);

namespace SnapAuth\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(InvalidSecretKey::class)]
#[Small]
class InvalidSecretKeyTest extends TestCase
{
    public function testConstruct(): void
    {
        $e = new InvalidSecretKey();
        self::assertStringContainsString('Invalid secret key', $e->getMessage());
    }
}
