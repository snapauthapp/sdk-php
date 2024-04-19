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
        // @phpstan-ignore-next-line BC enforcement check
        self::assertInstanceOf(Client::class, $client);
    }

    public function testConstructSecretKeyAutodetectMissing(): void
    {
        assert(getenv('SNAPAUTH_SECRET_KEY') === false);
        self::expectException(ApiError::class);
        self::expectExceptionMessage('Secret key missing.');
        new Client();
    }

    public function testSecretKeyValidation(): void
    {
        self::expectException(ApiError::class);
        new Client(secretKey: 'not_a_secret');
    }

    public function testKeyIsRedactedInDebugInfo(): void
    {
        $client = new Client(secretKey: 'secret_abc_123');
        $result = print_r($client, true);
        self::assertStringNotContainsString('secret_abc_123', $result);
    }
}
