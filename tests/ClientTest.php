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

    public function testConstructSecretKeyAutodetectInvalid(): void
    {
        assert(getenv('SNAPAUTH_SECRET_KEY') === false);
        putenv('SNAPAUTH_SECRET_KEY=invalid');
        self::expectException(ApiError::class);
        self::expectExceptionMessage('Invalid secret key.');
        new Client();
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

    public function testFailing(): void
    {
        self::assertSame(1, 3);
    }

    public function tearDown(): void
    {
        // Note: trailing = sets it to empty string. This actually clears it.
        putenv('SNAPAUTH_SECRET_KEY');
    }
}
