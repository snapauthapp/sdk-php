<?php

declare(strict_types=1);

namespace SnapAuth\Transport;

use Composer\InstalledVersions;
use JsonException;

use function curl_close;
use function curl_errno;
use function curl_exec;
use function curl_getinfo;
use function curl_init;
use function curl_setopt_array;
use function curl_version;

use const CURLE_OK;
use const CURLINFO_RESPONSE_CODE;
use const CURLOPT_HTTPHEADER;
use const CURLOPT_POST;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_URL;
use const JSON_THROW_ON_ERROR;

final class Curl implements TransportInterface
{
    public function makeApiCall(string $url, array $params): Response
    {
        // TODO: PSR-xx
        $json = json_encode($params, JSON_THROW_ON_ERROR);
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
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
                $this->error();
            }

            // Handle non-200s, non-JSON (severe upstream error)
            assert(is_string($response));
            $decoded = json_decode($response, true, flags: JSON_THROW_ON_ERROR);
            assert(is_array($decoded));
            return new Response(code: $code, decoded: $decoded);
        } catch (JsonException) {
            $this->error();
        } finally {
            curl_close($ch);
        }
    }
}
