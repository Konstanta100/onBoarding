<?php


namespace App\Dto\Request;


interface AcceptPasswordRequest
{
    public function getUserId(): int;

    public function getToken(): string;

    public function getPassword(): string;
}