<?php

declare(strict_types=1);

namespace App\Service;

use Redis;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class ContactRegister
{
    protected UserService $userService;

    protected EventDispatcherInterface $eventDispatcher;

    protected Redis $redis;

    function __construct(
        UserService $userService,
        EventDispatcherInterface $eventDispatcher,
        Redis $redis
    )
    {
        $this->userService = $userService;
        $this->eventDispatcher = $eventDispatcher;
        $this->redis = $redis;
    }
}