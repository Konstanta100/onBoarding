<?php


namespace App\Dto\Request;


interface ContactConfirmRequest
{
    public function getToken(): string;
}