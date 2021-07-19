<?php


namespace App\Service;


use App\Dto\RegisterByEmailRequest;
use App\Dto\RegisterRequest;
use App\Entity\User;

class RegisterService
{
    private RegisterStrategy $registerStrategy;

    private UserService $userService;

    public function __construct(
        UserService $userService,
        RegisterStrategy $registerStrategy
    )
    {
        $this->userService = $userService;
        $this->registerStrategy = $registerStrategy;
    }

    /**
     * @param RegisterStrategy $registerStrategy
     */
    public function setStrategy(RegisterStrategy $registerStrategy)
    {
        $this->registerStrategy = $registerStrategy;
    }

    public function initiate(RegisterRequest $registerRequest)
    {
        $user = $this->registerStrategy->initiate($registerRequest);
    }
}