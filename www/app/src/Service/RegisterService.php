<?php


namespace App\Service;

use App\Dto\RegisterRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

    public function initiate(RegisterRequest $registerRequest): void
    {
        $this->registerStrategy->initiate($registerRequest);
    }
}