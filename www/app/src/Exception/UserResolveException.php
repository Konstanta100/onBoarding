<?php


namespace App\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserResolveException extends Exception implements ApiException
{
    public function __construct(
        $message = 'Not valid username or password',
        $code = Response::HTTP_BAD_REQUEST,
        Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}