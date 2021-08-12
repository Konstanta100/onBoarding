<?php

declare(strict_types=1);


namespace App\Service;


use App\Dto\Request\RegisterRequest;
use App\Entity\ChannelContact;
use App\Entity\User;
use App\Exception\UserPasswordRepeatException;
use App\Repository\UserSourceInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private UserSourceInterface $userSource;

    protected UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        UserSourceInterface $userSource,
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

    /**
     * @param RegisterRequest $registerRequest
     * @return User
     */
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
        if ($user->getChannelConfirmed() === ChannelContact::NONE) {
            $user->setChannelConfirmed(ChannelContact::EMAIL);
        }

        $this->updatePassword($user, $newPassword);
    }

    /**
     * @param User $user
     * @param string $newPassword
     * @throws UserPasswordRepeatException
     */
    public function updatePassword(User $user, string $newPassword): void
    {
        $newPassword = $this->passwordEncoder->encodePassword($user, $newPassword);

        if ($user->getPassword() === $newPassword) {
            throw new UserPasswordRepeatException();
        }

        $user->setPassword($newPassword);
        $this->userSource->save($user);
    }
}