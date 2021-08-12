<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\UserEvents;
use App\Event\EmailRegisterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailRegisterSubscriber implements EventSubscriberInterface
{
    protected MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserEvents::EMAIL_REGISTER => 'onEmailRegister',
        ];
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function onEmailRegister(EmailRegisterEvent $event): void
    {
        $token = $event->getToken();

        $subject = "Подтверждение почты на сайте " . $_SERVER['HTTP_HOST'];
        $subject = "=?utf-8?B?" . base64_encode($subject) . "?=";

        $textLink = 'http://' . $_SERVER['HTTP_HOST'] . '/confirmEmail';
        $link = "<a href={$textLink}>{$textLink}</a>";

        $message = 'Здравствуйте! Чтобы войти, перейдите по ссылке ниже. ВНИМАНИЕ!
        Ссылка действительная 24 часа. Если вы не запрашивали ссылку для входа,
        просто проигнорируйте это письмо.';

        $methodBody = json_encode(['token' => $token, 'password' => '']);

        $email = (new Email())->to($event->getUser()->getEmail())
            ->subject($subject)
            ->text($textLink)
            ->html("<p>{$message}</p><p>{$link}</p><p><code>{$methodBody}</code></p>");

        $this->mailer->send($email);
    }
}