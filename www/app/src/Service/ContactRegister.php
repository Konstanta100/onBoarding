<?php


namespace App\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class ContactRegister
{
    protected UserService $userService;

    protected EventDispatcherInterface $eventDispatcher;

    function __construct(UserService $userService, EventDispatcherInterface $eventDispatcher)
    {
        $this->userService = $userService;
        $this->eventDispatcher = $eventDispatcher;
    }
}