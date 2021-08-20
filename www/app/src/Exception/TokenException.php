<?php


namespace App\Exception;


class TokenException extends ApiException
{
    public function __construct(
        $message = "Token is invalid",
        $code = Response::HTTP_BAD_REQUEST,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}