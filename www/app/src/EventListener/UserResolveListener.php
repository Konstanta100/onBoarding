<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\ChannelContact;
use App\Entity\User;
use App\Exception\UserBlockException;
use App\Exception\UserResolveException;
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
     * @throws UserResolveException
     * @throws UserBlockException
     */
    public function onUserResolve(UserResolveEvent $event): void
    {
        $user = $this->userService->findByEmail($event->getUsername());

        if (!$user instanceof User){
            throw new UserResolveException();
        }

        if ($user->getChannelConfirmed() === ChannelContact::NONE){
            throw new UserResolveException();
        }

        if($this->userPasswordEncoder->isPasswordValid($user, $event->getPassword())){
            throw new UserResolveException();
        }

        if(!$user->isActive()){
            throw new UserBlockException();
        }

        $event->setUser($user);
    }
}