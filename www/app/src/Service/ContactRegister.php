<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Request\ContactConfirmRequest;
use App\Dto\Request\RegisterRequest;
use App\Dto\Response\DataResponse;
use App\Entity\User;
use App\Exception\TokenException;
use App\Exception\UserBlockException;
use App\Exception\UserNotFoundException;
use Redis;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class ContactRegister
{
    protected UserService $userService;

    protected EventDispatcherInterface $eventDispatcher;

    protected Redis $redis;

    function __construct(
        UserService $userService,
        EventDispatcherInterface $eventDispatcher,
        Redis $redis
    )
    {
        $this->userService = $userService;
        $this->eventDispatcher = $eventDispatcher;
        $this->redis = $redis;
    }

    abstract protected function findUserByContact(string $contact): ?User;

    abstract protected function createUser(RegisterRequest $request): User;

    abstract protected function isConfirmedContact(User $user): bool;

    abstract protected function confirmeContact(User $user): void;

    abstract protected function sendRegister(User $user, string $token): void;

    abstract protected function sendConfirm(User $user): void;

    abstract protected function sendRecoverPassword(User $user, string $token): void;

    abstract protected function sendConfirmPassword(User $user): void;

    abstract protected function getRegisterKey(string $userId): string;

    final public function initiate(RegisterRequest $request): DataResponse
    {
        $user = $this->findUserByContact($request->getContact());

        if ($user instanceof User) {
            if ($this->isConfirmedContact($user)) {
                return new DataResponse('User already register', Response::HTTP_BAD_REQUEST);
            }
        } else {
            $user = $this->createUser($request);
        }

        $token = $this->createToken($user);
        $this->sendRegister($user, $token);

        return new DataResponse('The register message was sent');
    }

    /**
     * @param ContactConfirmRequest $request
     * @return DataResponse
     * @throws UserNotFoundException
     * @throws TokenException
     */
    final public function confirm(ContactConfirmRequest $request): DataResponse
    {
        $token = $request->getToken();

        $user = $this->findUserByToken($token);

        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        $this->confirmeContact($user);
        $this->deleteToken($token);
        $this->sendConfirm($user);

        return new DataResponse('User data is confirmed');
    }

    /**
     * @param ContactInfoRequest $request
     * @return DataResponse
     * @throws UserBlockException
     */
    final public function recoverPassword(ContactInfoRequest $request): DataResponse
    {
        $user = $this->findUserByContact($request->getContact());

        if (!$user instanceof User) {
            return new DataResponse('User is not register', Response::HTTP_BAD_REQUEST);
        }

        if (!$user->isActive()) {
            throw new UserBlockException();
        }

        if ($this->isConfirmedContact($user)) {
            return new DataResponse('User is not register', Response::HTTP_BAD_REQUEST);
        }

        $token = $this->createToken($user);
        $this->sendRecoverPassword($user, $token);

        return new DataResponse('The recover-password message was sent');
    }

    /**
     * @param ContactConfirmRequest $request
     * @return DataResponse
     * @throws UserNotFoundException
     */
    final public function confirmPassword(ContactConfirmRequest $request): DataResponse
    {
        $token = $request->getToken();

        $user = $this->findUserByToken($token);

        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        $this->updatePassword($user, $request->getPassword());
        $this->deleteToken($token);
        $this->sendConfirm($user);

        return new DataResponse('User data is confirmed');
    }

    /**
     * @param User $user
     * @return string
     */
    protected function createToken(User $user): string
    {
        $userId = $user->getId();
        $token = md5($userId . $user->getEmail() . time());
        //TODO Добавить Exception в случае неудачи установленного значения
        $this->redis->setex($token, 3600, $userId);

        return $token;
    }

    /**
     * @param string $token
     */
    protected function deleteToken(string $token): void
    {
        $this->redis->del($token);
    }

    /**
     * @throws TokenException
     */
    protected function findUserByToken(string $token): ?User
    {
        $userId = $this->redis->get($token);

        if($userId === false){
            throw new TokenException();
        }

        return $this->userService->findById($userId);
    }

    public function updatePassword(User $user, string $newPassword): void
    {
        $this->userService->updatePassword($user, $newPassword);
    }
}