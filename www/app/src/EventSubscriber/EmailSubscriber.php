<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\EmailEvent;
use App\Event\UserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailSubscriber implements EventSubscriberInterface
{
    protected MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserEvents::SEND_EMAIL => 'onSendEmail',
        ];
    }

    /**
     * @param EmailEvent $event
     */
    public function onSendEmail(EmailEvent $event): void
    {
        $email = (new Email())->to($event->getUser()->getEmail())
            ->subject($event->getSubject())
            ->text($event->getTextLink())
            ->html($event->getTemplate());

        $this->mailer->send($email);
    }
}