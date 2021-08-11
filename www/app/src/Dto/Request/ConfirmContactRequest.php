<?php


namespace App\Dto\Request;


interface ConfirmContactRequest
{
    public function getUserId(): ?int;

    public function getToken(): ?string;

    public function getPassword(): ?string;
}