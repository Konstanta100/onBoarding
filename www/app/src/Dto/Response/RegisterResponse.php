<?php

declare(strict_types=1);

namespace App\Dto\Response;


class RegisterResponse
{
    private string $message;

    private int $code;

    public function __construct($message = '', int $code = 200)
    {
        $this->message = $message;
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }
}