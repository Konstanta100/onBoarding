<?php


namespace App\Service;


use App\Dto\RegisterRequest;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EmailRegister extends ContactRegister implements RegisterStrategy
{
    public function initiate(RegisterRequest $registerRequest): void
    {
        $user = $this->userService->findByEmail($registerRequest->getContact());

        if ($user instanceof User) {
            throw new BadRequestHttpException('User with this email already exists', null, 400);
        }

        $this->userService->registerByEmail($registerRequest);
    }

    public function confirmContact()
    {
        // TODO: Implement confirmContact() method.
    }
}