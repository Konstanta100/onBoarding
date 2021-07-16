<?php


namespace App\Service;


use App\Dto\RegisterByEmailRequest;
use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    public function isUser(RegisterByEmailRequest $registerRequest): ?User
    {
        return $this->userRepository->findByEmail($registerRequest->getEmail());
    }

    public function createUser()
    {
        $this->userRepository->addUser();
    }

}