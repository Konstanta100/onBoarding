<?php


namespace App\Exception;


use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserPasswordRepeatException extends ApiException
{
    public function __construct(
        $message = 'The password was used earlier',
        $code = Response::HTTP_BAD_REQUEST,
        Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}