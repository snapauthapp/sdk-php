<?php

declare(strict_types=1);

namespace SnapAuth;

/**
 * @phpstan-import-type UserData from User
 * @phpstan-type AuthResponseData array{
 *   user: UserData,
 * }
 */
class AuthResponse
{
    public readonly User $user;

    /**
     * @param AuthResponseData $data
     */
    public function __construct(array $data)
    {
        $this->user = new User($data['user']);
    }
}
