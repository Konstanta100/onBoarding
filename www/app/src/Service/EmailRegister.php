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
use App\Exception\TokenErrors;
use App\Exception\UserNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class EmailRegister extends ContactRegister implements RegisterStrategy
{
    /**
     * @param RegisterRequest $request
     * @return DataResponse
     */
    public function initiate(RegisterRequest $request): DataResponse
    {
        $user = $this->userService->findByEmail($request->getContact());

        if ($user instanceof User) {
            return new DataResponse('User with this email already exists', Response::HTTP_BAD_REQUEST);
        }

        if (!$user->isActive()) {
            return new DataResponse('Email confirmation is required', Response::HTTP_BAD_REQUEST);
        }

        $redisKey = $this->getRedisKey($user->getId());

        if ($this->redis->exists($redisKey)) {
            return new DataResponse('Confirmation email has already been sent', Response::HTTP_BAD_REQUEST);
        };

        $user = $this->userService->createByEmail($request);
        $this->sendConfirm($user);

        return new DataResponse('The email was sent to ' . $user->getEmail());
    }

    /**
     * @param ConfirmUserRequest $request
     * @return DataResponse
     * @throws UserNotFoundException
     */
    public function confirmUser(ConfirmUserRequest $request): DataResponse
    {
        $userId = $request->getUserId();
        $redisKey = $this->getRedisKey($userId);
        $token = $this->redis->get($redisKey);

        if ($token === false) {
            return new DataResponse(TokenErrors::TOKEN_NOT_EXIST, Response::HTTP_BAD_REQUEST);
        }

        if ($token !== $request->getToken()) {
            return new DataResponse(TokenErrors::TOKEN_NOT_MATCH, Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userService->findById($userId);

        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        $this->redis->del($redisKey);
        $this->userService->activate($user);

        return new DataResponse('The email is confirmed');
    }

    /**
     * @param User $user
     * @return string
     */
    private function createRedisToken(User $user): string
    {
        $redisKey = $this->getRedisKey($user->getId());
        $token = md5($user->getEmail() . time());
        $this->redis->set($redisKey, $token, 86400);

        return $token;
    }

    /**
     * @throws UserNotFoundException
     */
    public function confirmContact(ConfirmContactRequest $request): DataResponse
    {
        $user = $this->userService->findByEmail($request->getContact());

        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        if ($user->isActive()) {
            return new DataResponse('User with this email already exists', Response::HTTP_BAD_REQUEST);
        }

        if ($this->redis->exists($this->getRedisKey($user->getId()))) {
            return new DataResponse('The confirmation email has already been sent', Response::HTTP_BAD_REQUEST);
        };

        $this->sendConfirm($user);

        return new DataResponse('The email was sent to ' . $user->getEmail());
    }

    private function sendConfirm(User $user): void
    {
        $token = $this->createRedisToken($user);
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
     * @param RecoverPasswordRequest $request
     * @return DataResponse
     * @throws UserNotFoundException
     */
    public function recoverPassword(RecoverPasswordRequest $request): DataResponse
    {
        $user = $this->userService->findActiveByEmail($request->getContact());

        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        $token = $this->createRedisToken($user);
        $event = new EmailRecoverPasswordEvent($user, $token);
        $this->eventDispatcher->dispatch($event, UserEvents::EMAIL_RECOVER_PASSWORD);

        return new DataResponse('The letter was sent to the email: ' . $user->getEmail());
    }

    /**
     * @throws UserNotFoundException
     */
    public function acceptPassword(AcceptPasswordRequest $request): DataResponse
    {
        $userId = $request->getUserId();
        $redisKey = $this->getRedisKey($userId);
        $token = $this->redis->get($redisKey);

        if ($token === false) {
            return new DataResponse(TokenErrors::TOKEN_NOT_EXIST, Response::HTTP_BAD_REQUEST);
        }

        if ($token !== $request->getToken()) {
            return new DataResponse(TokenErrors::TOKEN_NOT_MATCH, Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userService->findById($userId);

        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        $this->redis->del($redisKey);
        $this->userService->updatePassword($user, $request->getPassword());

        return new DataResponse('Password changed');
    }
}