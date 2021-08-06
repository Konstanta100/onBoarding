<?php

declare(strict_types=1);

namespace App\Dto\Request;


class ConfirmUserRequest
{
    private int $userId;

    private string $token;

    public function __construct(int $userId, string $token)
    {
        $this->userId = $userId;
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}