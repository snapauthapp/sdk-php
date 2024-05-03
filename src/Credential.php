<?php

declare(strict_types=1);

namespace SnapAuth;

class Credential
{
    public readonly string $id;

    // @phpstan-ignore-next-line
    public function __construct(array $data)
    {
        $this->id = $data['id'];
    }
}
