<?php

declare(strict_types=1);

namespace App\Dto\Response;


use Symfony\Component\HttpFoundation\Response;

class DataResponse
{
    private string $message;

    private int $code;

    public function __construct($message = '', int $code = Response::HTTP_OK)
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