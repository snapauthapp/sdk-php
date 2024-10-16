<?php

declare(strict_types=1);

namespace SnapAuth;

enum ErrorCode: string
{
    case AuthenticatingUserAccountNotFound = 'AuthenticatingUserAccountNotFound';
    case EntityNotFound = 'EntityNotFound';
    case HandleCannotChange = 'HandleCannotChange';
    case HandleInUseByDifferentAccount = 'HandleInUseByDifferentAccount';
    case InvalidAuthorizationHeader = 'InvalidAuthorizationHeader';
    case InvalidInput = 'InvalidInput';
    case PermissionViolation = 'PermissionViolation';
    case PublishableKeyNotFound = 'PublishableKeyNotFound';
    case RegisteredUserLimitReached = 'RegisteredUserLimitReached';
    case SecretKeyExpired = 'SecretKeyExpired';
    case SecretKeyNotFound = 'SecretKeyNotFound';
    case TokenExpired = 'TokenExpired';
    case TokenNotFound = 'TokenNotFound';
    case UsingDeactivatedCredential = 'UsingDeactivatedCredential';

    /**
     * This is a catch-all code if the API has returned an error code that's
     * unknown to this SDK. Often this means that a new SDK version will handle
     * the new code.
     */
    case Unknown = '(unknown)';
}
