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

    public function initiate(RegisterRequest $request): DataResponse
    {
        return $this->registerStrategy->initiate($request);
    }

    public function confirm(ConfirmUserRequest $request): DataResponse
    {
        return $this->registerStrategy->confirmUser($request);
    }

    public function confirmContact(ConfirmContactRequest $request): DataResponse
    {
        return $this->registerStrategy->confirmContact($request);
    }

    public function recoverPassword(RecoverPasswordRequest $request): DataResponse
    {
        return $this->registerStrategy->recoverPassword($request);
    }

    public function acceptPassword(AcceptPasswordRequest $request): DataResponse
    {
        return $this->registerStrategy->acceptPassword($request);
    }
}