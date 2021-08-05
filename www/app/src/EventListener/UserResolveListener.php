<?php

namespace App\EventListener;

use App\Service\UserService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Trikoder\Bundle\OAuth2Bundle\Event\UserResolveEvent;

final class UserResolveListener
{
    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * @param UserService $userService
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserService $userService, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userService = $userService;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param UserResolveEvent $event
     */
    public function onUserResolve(UserResolveEvent $event): void
    {
        $user = $this->userService->findByEmail($event->getUsername());

        if (null === $user) {
            return;
        }

        if (!$this->userPasswordEncoder->isPasswordValid($user, $event->getPassword())) {
            return;
        }

        $event->setUser($user);
    }
}