<?php

declare(strict_types=1);

namespace SnapAuth;

class User
{
    public readonly string $id;
    public readonly string $handle;

    // @phpstan-ignore-next-line
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->handle = $data['handle'];
    }
}
