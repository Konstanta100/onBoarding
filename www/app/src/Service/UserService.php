<?php

declare(strict_types=1);


namespace App\Service;


use App\Dto\Request\RegisterRequest;
use App\Entity\User;
use App\Repository\IUserSource;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private IUserSource $userSource;

    protected UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        IUserSource $userSource,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->userSource = $userSource;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->userSource->findByEmail($email);
    }

    public function createByEmail(RegisterRequest $registerRequest): User
    {
        $user = new User();
        $user->setEmail($registerRequest->getContact());
        $this->userSource->save($user);

        return $user;
    }

    /**
     * @param string $userId
     * @return User|null
     */
    public function findById(string $userId): ?User
    {
        return $this->userSource->findById($userId);
    }

    public function confirmByEmail(User $user, string $newPassword): void
    {
        $user->setActive(true);
        $this->updatePassword($user, $newPassword);
    }

    public function updatePassword(User $user, string $newPassword)
    {
        $newPassword = $this->passwordEncoder->encodePassword($user, $newPassword);
        $user->setPassword($newPassword);
        $this->userSource->save($user);
    }
}