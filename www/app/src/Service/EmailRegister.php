<?php


namespace App\Service;


use App\Dto\RegisterRequest;
use App\Entity\User;
use App\Event\UserEvents;
use App\Event\EmailRegisterEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EmailRegister extends ContactRegister implements RegisterStrategy
{
    public function initiate(RegisterRequest $registerRequest): void
    {
        $user = $this->userService->findByEmail($registerRequest->getContact());

        if ($user instanceof User) {
            throw new BadRequestHttpException('User with this email already exists', null, 400);
        }

        $user = $this->userService->registerByEmail($registerRequest);

        $event = new EmailRegisterEvent($user);
        $this->eventDispatcher->dispatch($event, UserEvents::EMAIL_REGISTER);
    }

    public function confirmContact()
    {
        // TODO: Implement confirmContact() method.
    }
}