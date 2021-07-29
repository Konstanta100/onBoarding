<?php


namespace App\Service;


use App\Dto\RegisterByEmailRequest;
use App\Dto\RegisterRequest;
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

    public function registerByEmail(RegisterRequest $registerRequest): User
    {
        $user = new User();
        $password = $this->passwordEncoder->encodePassword($user, $registerRequest->getPassword());
        $user->setPassword($password);
        $user->setEmail($registerRequest->getContact());
        $this->userRepository->save($user);

        return $user;
    }
}