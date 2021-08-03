<?php

declare(strict_types=1);

namespace App\Service;


use App\Dto\Request\ConfirmContactRequest;
use App\Dto\Request\ConfirmUserRequest;
use App\Dto\Response\RegisterResponse;
use App\Dto\Request\RegisterRequest;
use App\Entity\User;
use App\Event\UserEvents;
use App\Event\EmailRegisterEvent;
use Symfony\Component\HttpFoundation\Response;

class EmailRegister extends ContactRegister implements RegisterStrategy
{
    public function initiate(RegisterRequest $registerRequest): RegisterResponse
    {
        $user = $this->userService->findByEmail($registerRequest->getContact());

        if ($user instanceof User) {
            $message = 'User with this email already exists';

            if (!$user->isActive()) {
                $message = 'User already exists. Email confirmation is required';
            }

            if ($this->redis->exists($this->createKey($user->getId()))) {
                $message = 'The confirmation email has already been sent';
            };

            return new RegisterResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userService->registerByEmail($registerRequest);

        return $this->sendConfirm($user);
    }

    public function confirmUser(ConfirmUserRequest $confirmRequest): RegisterResponse
    {
        $userId = $confirmRequest->getUserId();

        $redisKey = $this->createKey($userId);

        if ($token = $this->redis->get($redisKey)) {
            if ($token === $confirmRequest->getCode()) {
                $this->redis->del($redisKey);
                $user = $this->userService->findById($userId);

                if (!$user instanceof User) {
                    return new RegisterResponse('User not found', Response::HTTP_NOT_FOUND);
                }

                $this->userService->activate($user);

                return new RegisterResponse('The email was confirm');
            }

            $message = 'The confirmation code did not match';
        } else {
            $message = 'The confirmation period has expired';
        }

        return new RegisterResponse($message, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param User $user
     * @return string
     */
    private function createToken(User $user): string
    {
        $redisKey = $this->createKey($user->getId());
        $token = md5($user->getEmail() . time());
        $this->redis->set($redisKey, $token, 86400);

        return $token;
    }

    public function confirmContact(ConfirmContactRequest $confirmContactRequest): RegisterResponse
    {
        $user = $this->userService->findByEmail($confirmContactRequest->getContact());

        if (!$user instanceof User) {
            return new RegisterResponse('User not found', Response::HTTP_NOT_FOUND);
        }

        if ($user->isActive()) {
            return new RegisterResponse('User with this email already exists',Response::HTTP_BAD_REQUEST);
        }

        if ($this->redis->exists($this->createKey($user->getId()))) {
            return new RegisterResponse('The confirmation email has already been sent',Response::HTTP_BAD_REQUEST);
        };

        return $this->sendConfirm($user);
    }

    private function sendConfirm(User $user): RegisterResponse
    {
        $token = $this->createToken($user);
        $event = new EmailRegisterEvent($user, $token);
        $this->eventDispatcher->dispatch($event, UserEvents::EMAIL_REGISTER);

        return new RegisterResponse('The letter was sent to the email: ' . $user->getEmail());
    }

    /**
     * @param int $userId
     * @return string
     */
    private function createKey(int $userId): string
    {
        return $userId . '_email_reg';
    }
}