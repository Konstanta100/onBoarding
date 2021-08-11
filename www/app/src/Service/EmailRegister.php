<?php

declare(strict_types=1);

namespace App\Service;


use App\Dto\Request\ConfirmContactRequest;
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

        if (!$user instanceof User) {
            $user = $this->userService->createByEmail($request);
        }

        if ($user->isActive()) {
            return new DataResponse('User with this email already register', Response::HTTP_BAD_REQUEST);
        }

        $redisKey = $this->getRedisKey($user->getId());

        if ($this->redis->exists($redisKey)) {
            return new DataResponse('The confirmation email has already been sent', Response::HTTP_BAD_REQUEST);
        };

        $this->eventDispatcher->dispatch(
            new EmailRegisterEvent($user, $this->createRedisToken($user)),
            UserEvents::EMAIL_REGISTER
        );

        return new DataResponse('The email was sent to ' . $user->getEmail());
    }

    /**
     * @param ConfirmContactRequest $request
     * @return DataResponse
     * @throws UserNotFoundException
     */
    public function confirm(ConfirmContactRequest $request): DataResponse
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
        $this->userService->confirmByEmail($user, $request->getPassword());

        return new DataResponse('User data is confirmed');
    }

    /**
     * @param RegisterRequest $request
     * @return DataResponse
     * @throws UserNotFoundException
     */
    public function recoverPassword(RegisterRequest $request): DataResponse
    {
        $user = $this->userService->findByEmail($request->getContact());

        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        $this->eventDispatcher->dispatch(
            new EmailRecoverPasswordEvent($user, $this->createRedisToken($user)),
            UserEvents::EMAIL_RECOVER_PASSWORD
        );

        return new DataResponse('The letter was sent to the email: ' . $user->getEmail());
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
     * @param int $userId
     * @return string
     */
    private function getRedisKey(int $userId): string
    {
        return $userId . '_email';
    }
}