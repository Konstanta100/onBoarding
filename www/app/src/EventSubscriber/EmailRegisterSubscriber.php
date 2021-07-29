<?php


namespace App\EventSubscriber;


use App\Event\UserEvents;
use App\Event\EmailRegisterEvent;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Redis;


class EmailRegisterSubscriber implements EventSubscriberInterface
{
    protected MailerInterface $mailer;

    protected Redis $redis;

    public function __construct(MailerInterface $mailer, Redis $redis)
    {
        $this->mailer = $mailer;
        $this->redis = $redis;
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
    public function onEmailRegister(EmailRegisterEvent $event)
    {
        $user = $event->getUser();
        $this->redis->set($user->getId(), $user->getEmail(), 100);
        var_dump($this->redis->get($user->getId()));
        sleep(5);
        var_dump($this->redis->ttl($user->getId()));
        die();

        $email = (new Email())->from('hello@example.com')
            ->to($event->getUser()->getEmail())
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);


        var_dump($user);
        die();
    }
}