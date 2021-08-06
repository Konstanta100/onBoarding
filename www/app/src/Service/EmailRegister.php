<?php

declare(strict_types=1);

namespace App\Service;


use App\Dto\Request\ConfirmContactRequest;
use App\Dto\Request\ConfirmUserRequest;
use App\Dto\Request\RecoverPasswordRequest;
use App\Dto\Response\RecoverResponse;
use App\Dto\Response\RegisterResponse;
use App\Dto\Request\RegisterRequest;
use App\Entity\User;
use App\Event\EmailRecoverPasswordEvent;
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

            if ($this->redis->exists($this->createRegisterKey($user->getId()))) {
                $message = 'The confirmation email has already been sent';
            };

            return new RegisterResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userService->registerByEmail($registerRequest);

        $this->sendConfirm($user);

        return new RegisterResponse('The letter was sent to the email: ' . $user->getEmail());
    }

    public function confirmUser(ConfirmUserRequest $confirmRequest): RegisterResponse
    {
        $userId = $confirmRequest->getUserId();

        $redisKey = $this->createRegisterKey($userId);

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
    private function createRegisterToken(User $user): string
    {
        $redisKey = $this->createRegisterKey($user->getId());
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
            return new RegisterResponse('User with this email already exists', Response::HTTP_BAD_REQUEST);
        }

        if ($this->redis->exists($this->createRegisterKey($user->getId()))) {
            return new RegisterResponse('The confirmation email has already been sent', Response::HTTP_BAD_REQUEST);
        };

        $this->sendConfirm($user);

        return new RegisterResponse('The letter was sent to the email: ' . $user->getEmail());
    }

    private function sendConfirm(User $user): void
    {
        $token = $this->createRegisterToken($user);
        $event = new EmailRegisterEvent($user, $token);
        $this->eventDispatcher->dispatch($event, UserEvents::EMAIL_REGISTER);
    }

    /**
     * @param int $userId
     * @return string
     */
    private function createRegisterKey(int $userId): string
    {
        return $userId . '_email_reg';
    }

    /**
     * @param RecoverPasswordRequest $recoverPasswordRequest
     * @return RecoverResponse
     */
    public function recoverPassword(RecoverPasswordRequest $recoverPasswordRequest): RecoverResponse
    {
        $user = $this->userService->findActiveByEmail($recoverPasswordRequest->getContact());

        if (!$user instanceof User) {
            return new RecoverResponse('User not found', Response::HTTP_NOT_FOUND);
        }

        $this->sendRecoverPassword($user);

        return new RecoverResponse('The letter was sent to the email: ' . $user->getEmail());
    }

    private function sendRecoverPassword(User $user): void
    {
        $token = $this->createRecoverPasswordToken($user);
        $event = new EmailRecoverPasswordEvent($user, $token);
        $this->eventDispatcher->dispatch($event, UserEvents::EMAIL_RECOVER_PASSWORD);
    }

    private function createRecoverPasswordToken(User $user): string
    {
        $redisKey = $this->createRecoverPasswordKey($user->getId());
        $token = md5($user->getEmail() . time());
        $this->redis->set($redisKey, $token, 86400);

        return $token;
    }

    /**
     * @param int $userId
     * @return string
     */
    private function createRecoverPasswordKey(int $userId): string
    {
        return $userId . '_email_rec_pas';
    }
}