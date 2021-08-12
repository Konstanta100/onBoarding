<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\EmailRecoverPasswordEvent;
use App\Event\UserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailRecoverPasswordSubscriber implements EventSubscriberInterface
{
    protected MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserEvents::EMAIL_RECOVER_PASSWORD => 'onEmailRecoverPassword',
        ];
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function onEmailRecoverPassword(EmailRecoverPasswordEvent $event): void
    {
        $token = $event->getToken();

        $subject = "Смена пароля на сайте " . $_SERVER['HTTP_HOST'];
        $subject = "=?utf-8?B?" . base64_encode($subject) . "?=";

        $textLink = 'http://' . $_SERVER['HTTP_HOST'] . '/acceptPasswordEmail';
        $link = "<a href={$textLink}>{$textLink}</a>";

        $message = 'Здравствуйте! Чтобы сменить пароль, пройдите по ссылке ниже.
        Указав метод POST и теле запроса в формате application/json новый пароль ключ "password".
        ВНИМАНИЕ! Ссылка действительная 24 часа. Если вы не запрашивали смену пароля проигнорируйте это письмо.';
        $methodBody = json_encode(['token' => $token, 'password' => '']);

        $email = (new Email())->to($event->getUser()->getEmail())
            ->subject($subject)
            ->text($textLink)
            ->html("<p>{$message}</p><p>$link</p><p><code>{$methodBody}</code></p>");

        $this->mailer->send($email);
    }
}