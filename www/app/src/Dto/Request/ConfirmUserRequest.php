<?php

declare(strict_types=1);

namespace App\Dto\Request;


class ConfirmUserRequest
{
    private int $userId;

    private string $code;

    public function __construct(int $userId, string $code)
    {
        $this->userId = $userId;
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}