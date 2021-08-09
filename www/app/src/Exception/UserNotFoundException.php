<?php


namespace App\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserNotFoundException extends Exception implements ApiException
{
    public function __construct($message = "User not found", $code = Response::HTTP_NOT_FOUND, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}