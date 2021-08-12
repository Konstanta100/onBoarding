<?php


namespace App\Dto\Request;


interface ConfirmContactRequest
{
    public function getUserId(): ?string;

    public function getToken(): ?string;

    public function getPassword(): ?string;
}