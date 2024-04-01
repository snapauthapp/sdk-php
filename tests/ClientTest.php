<?php

declare(strict_types=1);

namespace SnapAuth;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \SnapAuth\Client
 */
class ClientTest extends TestCase
{
    public function testConstructApi(): void
    {
        $client = new Client(secretKey: 'secret_abc_123');
        self::assertInstanceOf(Client::class, $client);
    }
}
