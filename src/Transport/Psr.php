<?php

declare(strict_types=1);

namespace SnapAuth\Transport;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\{RequestFactoryInterface, StreamFactoryInterface};

final class Psr implements TransportInterface
{
    public function __construct(
        public readonly ClientInterface $client,
        public readonly RequestFactoryInterface $requestFactory,
        public readonly StreamFactoryInterface $streamFactory,
    ) {
    }

    public function makeApiCall(string $route, array $params): array
    {
        $json = json_encode($params, JSON_THROW_ON_ERROR);
        $stream = $this->streamFactory->createStream($json);

        $request = $this->requestFactory
            ->createRequest(method: 'POST', uri: $uri)
            ->withHeader('Authoriation', 'Basic blahblah')
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-type', 'application/json')
            ->withHeader('Content-length', strlen($json))
            ->withHeader('User-agent', 'blahblah psr')
            ->withHeader('X-SDK', 'php/%s');

        // try/catch
        $response = $this->client->sendRequest($request);

        $code = $response->getStatusCode();
        if ($code >= 300) {
            // error
        }

        $responseJson = (string) $response->getBody();
        // decode, index
    }
}
