<?php

declare(strict_types=1);

namespace App\Service;


use App\Dto\Request\ContactConfirmRequest;
use App\Dto\Response\DataResponse;
use App\Dto\Request\RegisterRequest;
use App\Entity\ChannelContact;
use App\Entity\User;
use App\Event\EmailConfirmEvent;
use App\Event\EmailConfirmPasswordEvent;
use App\Event\EmailEvent;
use App\Event\EmailRecoverPasswordEvent;
use App\Event\UserEvents;
use App\Event\EmailRegisterEvent;
use App\Exception\TokenException;
use App\Exception\UserBlockException;
use App\Exception\UserNotFoundException;
use App\Exception\UserPasswordRepeatException;
use Symfony\Component\HttpFoundation\Response;

class EmailRegister extends ContactRegister
{
    /**
     * @param string $contact
     * @return User|null
     */
    protected function findUserByContact(string $contact): ?User
    {
        return $this->userService->findByEmail($contact);
    }

    /**
     * @param RegisterRequest $request
     * @return User
     * @throws UserPasswordRepeatException
     */
    protected function createUser(RegisterRequest $request): User
    {
        return $this->userService->createByEmail($request);
    }

    /**
     * @param User $user
     * @return bool
     */
    protected function isConfirmedContact(User $user): bool
    {
        $newChannel = $user->getChannelConfirmed() | ChannelContact::EMAIL;

        return $user->getChannelConfirmed() === $newChannel;
    }

    protected function confirmeContact(User $user): void
    {
        $this->userService->confirmEmail($user);
    }

    protected function sendRegister(User $user, string $token): void
    {
        $this->eventDispatcher->dispatch(new EmailRegisterEvent($user, $token), SEND_EMAIL);
    }

    protected function sendConfirm(User $user): void
    {
        $this->eventDispatcher->dispatch(new EmailConfirmEvent($user), UserEvents::SEND_EMAIL);
    }

    protected function sendRecoverPassword(User $user, string $token): void
    {
        $this->eventDispatcher->dispatch(new EmailRecoverPasswordEvent($user, $token), UserEvents::SEND_EMAIL);
    }

    protected function sendConfirmPassword(User $user): void
    {
        $this->eventDispatcher->dispatch(new EmailConfirmPasswordEvent($user), UserEvents::SEND_EMAIL);
    }

    /**
     * @param string $userId
     * @return string
     */
    protected function getRegisterKey(string $userId): string
    {
        return $userId . '_email';
    }
}