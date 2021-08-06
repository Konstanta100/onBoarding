<?php


namespace App\Dto\Request;


interface RecoverPasswordRequest
{
    public function getContact(): string;
}