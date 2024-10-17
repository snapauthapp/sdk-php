<?php

declare(strict_types=1);

namespace SnapAuth;

use Composer\InstalledVersions;
use JsonException;
use SensitiveParameter;

use function assert;
use function curl_close;
use function curl_errno;
use function curl_exec;
use function curl_getinfo;
use function curl_init;
use function curl_setopt_array;
use function curl_version;
use function is_array;
use function is_string;
use function json_decode;
use function json_encode;
use function sprintf;
use function strlen;

use const CURLE_OK;
use const CURLINFO_RESPONSE_CODE;
use const CURLOPT_HTTPHEADER;
use const CURLOPT_POST;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_URL;
use const JSON_THROW_ON_ERROR;

/**
 * SDK Prototype. This makes no attempt to short-circuit the network for
 * internal use, forcing a completely dogfooded experience.
 *
 * TODO: make testable, presumably via PSR-18
 * TODO: make an interface so the entire client can be mocked by developers
 */
class Client
{
    private const DEFAULT_API_HOST = 'https://api.snapauth.app';

    private string $secretKey;

    public function __construct(
        #[SensitiveParameter] ?string $secretKey = null,
        private string $apiHost = self::DEFAULT_API_HOST,
    ) {
        // Auto-detect if not provided
        if ($secretKey === null) {
            $env = getenv('SNAPAUTH_SECRET_KEY');
            if ($env === false) {
                throw new Exception\MissingSecretKey();
            }
            $secretKey = $env;
        }
        if (!str_starts_with($secretKey, 'secret_')) {
            throw new Exception\InvalidSecretKey();
        }

        $this->secretKey = $secretKey;
    }

    public function verifyAuthToken(string $authToken): AuthResponse
    {
        return new AuthResponse($this->makeApiCall(
            route: '/auth/verify',
            params: [
                'token' => $authToken,
            ]
        ));
    }

    /**
     * @param array{
     *   username?: string,
     *   id: string,
     * } $user
     */
    public function attachRegistration(string $regToken, array $user): Credential
    {
        return new Credential($this->makeApiCall(
            route: '/credential/create',
            params: [
                'token' => $regToken,
                'user' => $user,
            ]
        ));
    }

    /**
     * @internal This method is made public for cases where APIs do not have
     * native SDK support, but is NOT considered part of the public, stable
     * API and is not subject to SemVer.
     *
     * @param mixed[] $params
     * @return mixed[]
     */
    public function makeApiCall(string $route, array $params): array
    {
        // TODO: PSR-xx
        $json = json_encode($params, JSON_THROW_ON_ERROR);
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => sprintf('%s%s', $this->apiHost, $route),
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . base64_encode(':' . $this->secretKey),
                'Accept: application/json',
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json),
                sprintf(
                    'User-Agent: php-sdk/%s curl/%s php/%s',
                    InstalledVersions::getVersion('snapauth/sdk'),
                    curl_version()['version'] ?? 'unknown',
                    PHP_VERSION,
                ),
                sprintf('X-SDK: php/%s', InstalledVersions::getVersion('snapauth/sdk')),
            ],
        ]);

        try {
            $response = curl_exec($ch);
            $errno = curl_errno($ch);
            $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

            if ($response === false || $errno !== CURLE_OK) {
                throw new Exception\Network($errno);
            }
        } finally {
            curl_close($ch);
        }

        assert(is_string($response), 'No response body despite CURLOPT_RETURNTRANSFER');
        try {
            $decoded = json_decode($response, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            // Received non-JSON response - wrap and rethrow
            throw new Exception\MalformedResponse('Received non-JSON response', $code);
        }

        if (!is_array($decoded) || !array_key_exists('result', $decoded)) {
            // Received JSON response in an unexpected format
            throw new Exception\MalformedResponse('Received JSON in an unexpected format', $code);
        }

        // Success!
        if ($decoded['result'] !== null) {
            assert($code >= 200 && $code < 300, 'Got a result with a non-2xx response');
            return $decoded['result'];
        }

        // The `null` result indicated an error. Parse out the response shape
        // more and throw an appropriate ApiError.
        if (!array_key_exists('errors', $decoded)) {
            throw new Exception\MalformedResponse('Error details missing', $code);
        }
        $errors = $decoded['errors'];
        if (!is_array($errors) || !array_is_list($errors) || count($errors) === 0) {
            throw new Exception\MalformedResponse('Error details are invalid or empty', $code);
        }

        $primaryError = $errors[0];
        if (
            !is_array($primaryError)
            || !array_key_exists('code', $primaryError)
            || !array_key_exists('message', $primaryError)
        ) {
            throw new Exception\MalformedResponse('Error details are invalid or empty', $code);
        }

        // Finally, the error details are known to be in the correct shape.
        throw new Exception\CodedError($primaryError['message'], $primaryError['code'], $code);
    }

    public function __debugInfo(): array
    {
        return [
            'apiHost' => $this->apiHost,
            'secretKey' => substr($this->secretKey, 0, 9) . '***' . substr($this->secretKey, -2),
        ];
    }
}
