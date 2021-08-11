<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Request\ConfirmContactRequest;
use App\Dto\Request\ConfirmUserRequest;
use App\Dto\Request\RecoverPasswordRequest;
use App\Dto\Request\RegisterRequest;
use App\Dto\Response\DataResponse;

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

    public function confirm(ConfirmContactRequest $request): DataResponse
    {
        return $this->registerStrategy->confirm($request);
    }

    public function recoverPassword(RegisterRequest $request): DataResponse
    {
        return $this->registerStrategy->recoverPassword($request);
    }
}