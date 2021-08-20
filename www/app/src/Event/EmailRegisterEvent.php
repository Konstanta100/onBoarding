<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\User;

/**
 * Class EmailRegisterEvent
 * @package App\Event
 */
class EmailRegisterEvent extends EmailEvent
{
    /**
     * EmailRegisterEvent constructor.
     * @param User $user
     * @param string $token
     */
    public function __construct(User $user, string $token)
    {
        parent::__construct($user);
        $subject = 'Подтверждение почты на сайте ' . $_SERVER['HTTP_HOST'];

        $this->convertSubject($subject);
        $this->textLink = "http://" . $_SERVER['HTTP_HOST'] . 'confirmEmail/token=' . $token;
        $this->createTemplate();
    }

    protected function createTemplate(): void
    {
        $message = 'Здравствуйте! Чтобы подтвердить свою учетную запись, перейдите по ссылке ниже. ВНИМАНИЕ!
            Ссылка действительная 24 часа. Если вы не запрашивали ссылку для входа,
            просто проигнорируйте это письмо.';
        $link = "<a href={$this->textLink}>{$this->textLink}</a>";
        $this->template = "<p>{$message}</p><p>{$link}</p>";
    }
}