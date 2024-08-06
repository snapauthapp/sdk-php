<?php

declare(strict_types=1);

namespace SnapAuth;

use DateTimeImmutable;

class Credential
{
    public readonly string $id;
    public readonly string $aaguid;
    public readonly string $name;
    public readonly bool $isActive;
    public readonly bool $isBackedUp;
    public readonly bool $isBackupEligible;
    public readonly bool $isUvInitialized;
    public readonly DateTimeImmutable $createdAt;
    /**
     * @var WebAuthn\AuthenticatorTransport[]
     */
    public readonly array $transports;

    /**
     * @param array{
     *   id: string,
     *   aaguid: string,
     *   name: string,
     *   isActive: bool,
     *   isBackedUp: bool,
     *   isBackupEligible: bool,
     *   isUvInitialized: bool,
     *   createdAt: int,
     *   transports: string[],
     * } $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->aaguid = $data['aaguid'];
        $this->name = $data['name'];
        $this->isActive = $data['isActive'];
        $this->isBackedUp = $data['isBackedUp'];
        $this->isBackupEligible = $data['isBackupEligible'];
        $this->isUvInitialized = $data['isUvInitialized'];

        $this->createdAt = (new DateTimeImmutable())->setTimestamp($data['createdAt']);

        // Ensure array_is_list if anything is filtered
        $this->transports = array_values(array_filter(
            // If other transport methods are added on the API (which itself
            // requires a WebAuthn spec bump), filter out unknown values
            array_map(WebAuthn\AuthenticatorTransport::tryFrom(...), $data['transports'])
        ));
    }
}
