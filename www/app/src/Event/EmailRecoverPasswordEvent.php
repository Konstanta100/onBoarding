<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\User;

/**
 * Class EmailRegisterEvent
 * @package App\Event
 */
class EmailRecoverPasswordEvent extends EmailEvent
{
    /**
     * EmailRegisterEvent constructor.
     * @param User $user
     * @param string $token
     */
    public function __construct(User $user, string $token)
    {
        parent::__construct($user);
        $subject = 'Смена пароля на сайте ' . $_SERVER['HTTP_HOST'];

        $this->convertSubject($subject);
        $this->textLink = "http://" . $_SERVER['HTTP_HOST'] . "confirmPasswordEmail/token=" . $token;
        $this->createTemplate();
    }

    protected function createTemplate(): void
    {
        $message = 'Здравствуйте! Чтобы сменить пароль, пройдите по ссылке ниже.
            ВНИМАНИЕ! Ссылка действительная 24 часа. Если вы не запрашивали смену пароля проигнорируйте это письмо.';
        $link = "<a href={$this->textLink}>{$this->textLink}</a>";
        $this->template = "<p>{$message}</p><p>{$link}</p>";
    }
}