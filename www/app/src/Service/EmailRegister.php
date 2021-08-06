<?php

declare(strict_types=1);

namespace App\Service;


use App\Dto\Request\AcceptPasswordRequest;
use App\Dto\Request\ConfirmContactRequest;
use App\Dto\Request\ConfirmUserRequest;
use App\Dto\Request\RecoverPasswordRequest;
use App\Dto\Response\DataResponse;
use App\Dto\Request\RegisterRequest;
use App\Entity\User;
use App\Event\EmailRecoverPasswordEvent;
use App\Event\UserEvents;
use App\Event\EmailRegisterEvent;
use Symfony\Component\HttpFoundation\Response;

class EmailRegister extends ContactRegister implements RegisterStrategy
{
    public function initiate(RegisterRequest $registerRequest): DataResponse
    {
        $user = $this->userService->findByEmail($registerRequest->getContact());

        if ($user instanceof User) {
            $message = 'User with this email already exists';

            if (!$user->isActive()) {
                $message = 'User already exists. Email confirmation is required';
            }

            if ($this->redis->exists($this->getRedisKey($user->getId()))) {
                $message = 'The confirmation email has already been sent';
            };

            return new DataResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userService->registerByEmail($registerRequest);

        $this->sendConfirm($user);

        return new DataResponse('The letter was sent to the email: ' . $user->getEmail());
    }

    public function confirmUser(ConfirmUserRequest $confirmRequest): DataResponse
    {
        $userId = $confirmRequest->getUserId();

        $redisKey = $this->getRedisKey($userId);

        $token = $this->redis->get($redisKey);

        if ($token === false) {
            return new DataResponse('Token is missing', Response::HTTP_BAD_REQUEST);
        }

        if ($token === $confirmRequest->getToken()) {
            return new DataResponse('Token did not match', Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userService->findById($userId);

        if (!$user instanceof User) {
            return new DataResponse('User not found', Response::HTTP_NOT_FOUND);
        }

        $this->redis->del($redisKey);

        $this->userService->activate($user);

        return new DataResponse('The email is confirmed');
    }

    /**
     * @param User $user
     * @return string
     */
    private function createRegisterToken(User $user): string
    {
        $redisKey = $this->getRedisKey($user->getId());
        $token = md5($user->getEmail() . time());
        $this->redis->set($redisKey, $token, 86400);

        return $token;
    }

    public function confirmContact(ConfirmContactRequest $confirmContactRequest): DataResponse
    {
        $user = $this->userService->findByEmail($confirmContactRequest->getContact());

        if (!$user instanceof User) {
            return new DataResponse('User not found', Response::HTTP_NOT_FOUND);
        }

        if ($user->isActive()) {
            return new DataResponse('User with this email already exists', Response::HTTP_BAD_REQUEST);
        }

        if ($this->redis->exists($this->getRedisKey($user->getId()))) {
            return new DataResponse('The confirmation email has already been sent', Response::HTTP_BAD_REQUEST);
        };

        $this->sendConfirm($user);

        return new DataResponse('The letter was sent to the email: ' . $user->getEmail());
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
    private function getRedisKey(int $userId): string
    {
        return $userId . '_email';
    }

    /**
     * @param RecoverPasswordRequest $recoverPasswordRequest
     * @return DataResponse
     */
    public function recoverPassword(RecoverPasswordRequest $recoverPasswordRequest): DataResponse
    {
        $user = $this->userService->findActiveByEmail($recoverPasswordRequest->getContact());

        if (!$user instanceof User) {
            return new DataResponse('User not found', Response::HTTP_NOT_FOUND);
        }

        $this->sendRecoverPassword($user);

        return new DataResponse('The letter was sent to the email: ' . $user->getEmail());
    }

    private function sendRecoverPassword(User $user): void
    {
        $token = $this->createRecoverPasswordToken($user);
        $event = new EmailRecoverPasswordEvent($user, $token);
        $this->eventDispatcher->dispatch($event, UserEvents::EMAIL_RECOVER_PASSWORD);
    }

    private function createRecoverPasswordToken(User $user): string
    {
        $redisKey = $this->getRedisKey($user->getId());
        $token = md5($user->getEmail() . time());
        $this->redis->set($redisKey, $token, 86400);

        return $token;
    }

    public function acceptPassword(AcceptPasswordRequest $acceptPasswordRequest): DataResponse
    {
        $userId = $acceptPasswordRequest->getUserId();

        $redisKey = $this->getRedisKey($userId);

        $token = $this->redis->get($redisKey);

        if ($token === false) {
            return new DataResponse('Token is missing', Response::HTTP_BAD_REQUEST);
        }

        if ($token === $acceptPasswordRequest->getToken()) {
            return new DataResponse('Token did not match', Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userService->findById($userId);

        if (!$user instanceof User) {
            return new DataResponse('User not found', Response::HTTP_NOT_FOUND);
        }

        $this->redis->del($redisKey);
        $this->userService->updatePassword($user, $acceptPasswordRequest->getPassword());

        return new DataResponse('Password changed');
    }
}