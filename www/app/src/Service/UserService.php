<?php

declare(strict_types=1);


namespace App\Service;


use App\Dto\Request\RegisterRequest;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private UserRepository $userRepository;

    protected UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findActiveByEmail(string $email): ?User
    {
        return $this->userRepository->findActiveByEmail($email);
    }


    public function registerByEmail(RegisterRequest $registerRequest): User
    {
        $user = new User();
        $password = $this->passwordEncoder->encodePassword($user, $registerRequest->getPassword());
        $user->setPassword($password);
        $user->setEmail($registerRequest->getContact());
        $this->userRepository->save($user);

        return $user;
    }

    /**
     * @param int $userId
     * @return User|null
     */
    public function findById(int $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }

    public function activate(User $user): void
    {
        $user->setActive(true);
        $this->userRepository->save($user);
    }

    public function updatePassword(User $user, string $password)
    {
        $user->setPassword($password);
    }
}