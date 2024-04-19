# SnapAuth PHP SDK

This is the official PHP SDK for [SnapAuth](https://www.snapauth.app).

[![Packagist Version](https://img.shields.io/packagist/v/snapauth/sdk)](https://packagist.org/packages/snapauth/sdk)
[![Test](https://github.com/snapauthapp/sdk-php/actions/workflows/test.yml/badge.svg)](https://github.com/snapauthapp/sdk-php/actions/workflows/test.yml)
[![Lint](https://github.com/snapauthapp/sdk-php/actions/workflows/lint.yml/badge.svg)](https://github.com/snapauthapp/sdk-php/actions/workflows/lint.yml)
[![Static analysis](https://github.com/snapauthapp/sdk-php/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/snapauthapp/sdk-php/actions/workflows/static-analysis.yml)


## Documentation

Full API and usage docs are available [at the official site](https://docs.snapauth.app/server.html#introduction).

## Installation

```bash
composer require snapauth/sdk
```

## Setup

Get your _secret_ key from the [dashboard](https://dashboard.snapauth.app).
Provide it to the `SnapAuth\Client` class:

```php
use SnapAuth\Client;

$yourSecret = getenv('SNAPAUTH_SECRET_KEY');
$snapAuth = new Client(secretKey: $yourSecret);
```

> [!TIP]
> Secret keys are specific to an environment and domain.
> We HIGHLY RECOMMEND using environment variables or another external storage mechanism.
> Avoid committing them to version control, as this can more easily lead to compromise.
>
> The SDK will auto-detect the `SNAPAUTH_SECRET_KEY` environment variable if you do not provide a value directly.

## Usage

### Registration

Once you obtain a registration token from your frontend, use the `Client` to complete the process and attach it to the user:

```php
$token = 'value_from_frontend'; // $_POST['snapauth_token'] or similar
$userInfo = [
  'id' => 'your_user_id',
  'handle' => 'your_user_handle',
];
$snapAuth->attachRegistration($token, $userInfo);
```

<!--
Registration returns an `AttachResponse` object, which contains a credential identifier.
You may store this information at your end, but it's not necessary in most cases.
-->

This activates the passkey and associates it with the user.
`$userInfo` will be provided back to you during authentication, so you know who is signing in.

`id` should be some sort of _stable_ identifer, like a database primary key.

`handle` can be anything you want, or omitted entirely.
It's a convenience during _client_ authentication so you don't need to look up the user id again.
This would commonly be the value a user provides to sign in, such as a username or email.

Both must be strings, and can be up to 255 characters long.
Lookups during authentication are **case-insensitive**.

> [!TIP]
> We strongly ENCOURAGE you to obfuscate any possibly sensitive information, such as email addresses.
> You can accomplish this by hashing the value.
> Be aware that to use the handle during authentication, you will want to replicate the obfuscation procedure on your frontend.

### Authentication

Like registration, you will need to obtain a token from your frontend provided by the client SDK.

Use the `verifyAuthToken` method to get information about the authentication process, in the form of an `AuthResponse` object.
This object contains the previously-registered User `id` and `handle`.

```php
$token = 'value_from_frontend'; // $_POST['snapauth_token'] or similar
$authInfo = $snapAuth->verifyAuthToken($token);

// Specific to your application:
$authenticatedUserId = $authInfo->user->id;

// Laravel:
use Illuminate\Support\Facades\Auth;
Auth::loginUsingId($authenticatedUserId);
```

## Error Handling

The SnapAuth SDK is written in a fail-secure manner, and will throw an exception if you're not on the successful path.
This helps ensure that your integration is easy and reliable.

You may choose to locally wrap API calls in a `try/catch` block, or let a general application-wide error handler deal with any exceptions.

All SnapAuth exceptions are an `instanceof \SnapAuth\ApiError`.

## Compatibility

We follow semantic versioning, and limit backwards-incompatible changes to major versions (the X in X.Y.Z) only.

The SnapAuth SDK is maintained for all versions of PHP with [current security support](https://www.php.net/supported-versions.php).
Since Composer will platform-detect your currently-installed version of PHP, dropping support for older versions is _not_ considered a backwards compatibility break (but you may be unable to install newer versions until updating to a supported version of PHP).

Anything marked as `@internal` or any `protected` or `private` method is not considered in scope for backwards-compatibility guarantees.
Similarly, all methods should be treated as ones that may throw an exception, and as such new types of exceptions are not considered a BC break either.
