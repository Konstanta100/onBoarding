<?php


namespace App\Service;

abstract class ContactRegister
{
    protected UserService $userService;

    function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
}