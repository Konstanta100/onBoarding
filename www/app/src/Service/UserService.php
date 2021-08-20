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
     * @param RegisterRequest $request
     * @return User
     * @throws UserPasswordRepeatException
     */
    public function createByEmail(RegisterRequest $request): User
    {
        $user = new User();
        $user->setEmail($request->getContact());
        $this->updatePassword($user, $request->getPassword());

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

    public function confirmEmail(User $user): void
    {
        $newChannel = $user->getChannelConfirmed() | ChannelContact::EMAIL;
        $user->setChannelConfirmed($newChannel);
        $this->userSource->save($user);
    }

    /**
     * @param User $user
     * @param string $newPassword
     * @throws UserPasswordRepeatException
     */
    public function updatePassword(User $user, string $newPassword): void
    {
        $newPassword = $this->passwordEncoder->encodePassword($user, $newPassword);

        //TODO create validator password
        if ($user->getPassword() === $newPassword) {
            throw new UserPasswordRepeatException();
        }

        $user->setPassword($newPassword);
        $this->userSource->save($user);
    }
}