<?php


namespace App\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserBlockException extends Exception implements ApiThrowableInterface
{
    public function __construct(
        $message = "User is blocked",
        $code = Response::HTTP_BAD_REQUEST,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}