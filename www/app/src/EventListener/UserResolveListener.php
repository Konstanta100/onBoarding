<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
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
        $user = $this->userService->findActiveByEmail($event->getUsername());

        if ($user instanceof User || !$this->userPasswordEncoder->isPasswordValid($user, $event->getPassword())) {
            throw new Exception('Not valid username or login', Response::HTTP_BAD_REQUEST);
        }

        $event->setUser($user);
    }
}