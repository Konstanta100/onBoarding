<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class EmailRegisterEvent
 * @package App\Event
 */
class EmailRegisterEvent extends Event
{
    protected User $user;

    /**
     * EmailRegisterEvent constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}