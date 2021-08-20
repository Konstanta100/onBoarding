<?php


namespace App\Event;


use App\Entity\User;

class EmailConfirmEvent extends EmailEvent
{
    /**
     * EmailConfirmEvent constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
        $subject = 'Аккаунт на сайте ' . $_SERVER['HTTP_HOST'] . 'подтвёрждён!!!';

        $this->convertSubject($subject);
        $this->createTemplate();
    }

    protected function createTemplate(): void
    {
        $message = 'Аккаунт успешно подтвержден, рады что выбрали нас !!!';
        $this->template = "<p>{$message}</p>";
    }
}