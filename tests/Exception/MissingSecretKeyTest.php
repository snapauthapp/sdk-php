<?php

declare(strict_types=1);

namespace SnapAuth\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(MissingSecretKey::class)]
#[Small]
class MissingSecretKeyTest extends TestCase
{
    public function testConstruct(): void
    {
        $e = new MissingSecretKey();
        self::assertStringContainsString('SNAPAUTH_SECRET_KEY', $e->getMessage());
    }
}
