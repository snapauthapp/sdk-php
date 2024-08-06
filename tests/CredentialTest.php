<?php

declare(strict_types=1);

namespace SnapAuth;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function assert;
use function file_get_contents;
use function is_array;
use function json_decode;
use function sprintf;

use const JSON_THROW_ON_ERROR;

#[CoversClass(Credential::class)]
#[Small]
class CredentialTest extends TestCase
{
    public function testDecodingFromApiResponse(): void
    {
        $data = $this->readFixture('credential1.json');
        $cred = new Credential($data);

        self::assertSame('ctl_2893f2Vg86463c8xV7wVv5PG', $cred->id);
        self::assertSame('fbfc3007-154e-4ecc-8c0b-6e020557d7bd', $cred->aaguid);
        self::assertTrue($cred->isActive);
        self::assertTrue($cred->isBackedUp);
        self::assertTrue($cred->isBackupEligible);
        self::assertTrue($cred->isUvInitialized);
        self::assertSame('iCloud Keychain', $cred->name);
        self::assertSame([
            WebAuthn\AuthenticatorTransport::Hybrid,
            WebAuthn\AuthenticatorTransport::Internal,
        ], $cred->transports);
        self::assertEquals(new DateTimeImmutable('2024-03-07T20:02:04Z'), $cred->createdAt);
    }

    public function testDecodingUsbFromApiResponse(): void
    {
        $data = $this->readFixture('credential2.json');
        $cred = new Credential($data);

        self::assertSame('ctl_28CWCw4G3R4MGCg2cc2ccvGr', $cred->id);
        self::assertSame('00000000-0000-0000-0000-000000000000', $cred->aaguid);
        self::assertTrue($cred->isActive);
        self::assertFalse($cred->isBackedUp);
        self::assertFalse($cred->isBackupEligible);
        self::assertFalse($cred->isUvInitialized);
        self::assertSame('Passkey', $cred->name);
        self::assertSame([WebAuthn\AuthenticatorTransport::Usb], $cred->transports);
        self::assertEquals(new DateTimeImmutable('2024-08-05T21:35:48Z'), $cred->createdAt);
    }

    /**
     * @return mixed[]
     */
    private function readFixture(string $path): array
    {
        $path = sprintf('%s/%s/%s', __DIR__, 'fixtures', $path);
        $json = file_get_contents($path);
        assert($json !== false);
        $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        assert(is_array($data));
        return $data;
    }
}
