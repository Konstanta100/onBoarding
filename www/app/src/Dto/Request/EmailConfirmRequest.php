<?php

declare(strict_types=1);

namespace App\Dto\Request;


class EmailConfirmRequest implements ContactConfirmRequest
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}