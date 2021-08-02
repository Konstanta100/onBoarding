<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Request\ConfirmEmailRequest;
use App\Dto\Request\ConfirmUserRequest;
use App\Dto\Request\RegisterRequest;
use App\Dto\Response\RegisterResponse;

class RegisterService
{
    private RegisterStrategy $registerStrategy;

    public function __construct(
        RegisterStrategy $registerStrategy
    )
    {
        $this->registerStrategy = $registerStrategy;
    }

    /**
     * @param RegisterStrategy $registerStrategy
     */
    public function setStrategy(RegisterStrategy $registerStrategy)
    {
        $this->registerStrategy = $registerStrategy;
    }

    public function initiate(RegisterRequest $registerRequest): RegisterResponse
    {
        return $this->registerStrategy->initiate($registerRequest);
    }

    public function confirm(ConfirmUserRequest $confirmRequest): RegisterResponse
    {
        return $this->registerStrategy->confirmUser($confirmRequest);
    }

    public function confirmContact(ConfirmEmailRequest $confirmEmailRequest): RegisterResponse
    {
        return $this->registerStrategy->confirmContact($confirmEmailRequest);
    }
}