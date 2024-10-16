<?php

declare(strict_types=1);

namespace SnapAuth\Exception;

use SnapAuth\ErrorCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(CodedError::class)]
#[Small]
class CodedErrorTest extends TestCase
{
    public function testFormattingFromKnownErrorCode(): void
    {
        $e = new CodedError('Missing parameter foo', 'InvalidInput', 400);
        self::assertSame(ErrorCode::InvalidInput, $e->errorCode);
    }

    public function testFormattingFromUnknownErrorCode(): void
    {
        $e = new CodedError('Something bad happened', 'SevereError', 400);
        self::assertSame(ErrorCode::Unknown, $e->errorCode);
    }
}
