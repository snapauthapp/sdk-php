<?php

declare(strict_types=1);

namespace SnapAuth;

use SensitiveParameter;

use function assert;
use function is_array;
use function is_string;
use function json_decode;
use function json_encode;
use function sprintf;
use function strlen;

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

    private Transport\TransportInterface $transport;

    public function __construct(
        #[SensitiveParameter] ?string $secretKey = null,
        private string $apiHost = self::DEFAULT_API_HOST,
        ?Transport\TransportInterface $transport = null,
    ) {
        // Auto-detect if not provided
        if ($secretKey === null) {
            $env = getenv('SNAPAUTH_SECRET_KEY');
            if ($env === false) {
                throw new ApiError(
                    'Secret key missing. It can be explictly provided, or it ' .
                    'can be auto-detected from the SNAPAUTH_SECRET_KEY ' .
                    'environment variable.',
                );
            }
            $secretKey = $env;
        }
        if (!str_starts_with($secretKey, 'secret_')) {
            throw new ApiError(
                'Invalid secret key. Please verify you copied the full value from the SnapAuth dashboard.',
            );
        }

        $this->secretKey = $secretKey;
        $this->transport = $transport ?? new Transport\Curl();
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
     *   handle?: string,
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
        $url = sprintf('%s%s', $this->apiHost, $route);
        $result = $this->transport->makeApiCall(url: $url, params: $params);
        if ($result->code >= 300) {
            $this->error();
        }
        $decoded = $result->decoded;
        return $decoded['result'];
    }

    /**
     * TODO: specific error info!
     */
    private function error(): never
    {
        throw new ApiError();
        // TODO: also make this more specific
    }

    public function __debugInfo(): array
    {
        return [
            'apiHost' => $this->apiHost,
            'secretKey' => substr($this->secretKey, 0, 9) . '***' . substr($this->secretKey, -2),
            'transport' => get_class($this->transport),
        ];
    }
}
