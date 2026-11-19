<?php

declare(strict_types=1);

namespace SnapAuth;

/**
 * @phpstan-type UserData array{
 *   id: string,
 * }
 */
class User
{
    public readonly string $id;

    /**
     * @param UserData $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
    }
}
