<?php


namespace App\Dto;


interface RegisterRequest
{
    public function getContact(): string;

    public function getPassword(): string;
}