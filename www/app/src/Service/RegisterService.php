<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Request\AcceptPasswordRequest;
use App\Dto\Request\ConfirmContactRequest;
use App\Dto\Request\ConfirmUserRequest;
use App\Dto\Request\RecoverPasswordRequest;
use App\Dto\Request\RegisterRequest;
use App\Dto\Response\RecoverResponse;
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

    public function initiate(RegisterRequest $registerRequest): DataResponse
    {
        return $this->registerStrategy->initiate($registerRequest);
    }

    public function confirm(ConfirmUserRequest $confirmRequest): DataResponse
    {
        return $this->registerStrategy->confirmUser($confirmRequest);
    }

    public function confirmContact(ConfirmContactRequest $confirmEmailRequest): DataResponse
    {
        return $this->registerStrategy->confirmContact($confirmEmailRequest);
    }

    public function recoverPassword(RecoverPasswordRequest $confirmEmailRequest): DataResponse
    {
        return $this->registerStrategy->recoverPassword($confirmEmailRequest);
    }

    public function acceptPassword(AcceptPasswordRequest $acceptPasswordRequest): DataResponse
    {
        return $this->registerStrategy->acceptPassword($acceptPasswordRequest);
    }
}