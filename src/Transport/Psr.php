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

    public function makeApiCall(string $url, array $params): Response
    {
        $json = json_encode($params, JSON_THROW_ON_ERROR);
        $stream = $this->streamFactory->createStream($json);

        $request = $this->requestFactory
            ->createRequest(method: 'POST', uri: $url)
            ->withHeader('Authoriation', 'Basic blahblah')
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-type', 'application/json')
            ->withHeader('Content-length', (string) strlen($json))
            ->withHeader('User-agent', 'blahblah psr')
            ->withHeader('X-SDK', 'php/%s');

        // try/catch
        $response = $this->client->sendRequest($request);

        $code = $response->getStatusCode();

        $responseJson = (string) $response->getBody();
        if (!json_validate($responseJson)) {
            // ??
        }
        $decoded = json_decode($responseJson, true, flags: JSON_THROW_ON_ERROR);
        assert(is_array($decoded));
        return new Response($code, $decoded);
    }
}
