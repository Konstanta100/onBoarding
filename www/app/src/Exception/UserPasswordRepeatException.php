<?php


namespace App\Exception;


use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserPasswordRepeatException extends Exception
{
    public function __construct(
        $message = 'The password was used earlier',
        $code = Response::HTTP_BAD_REQUEST,
        Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}