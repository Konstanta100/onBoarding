<?php


namespace App\Event;


final class UserEvents
{
    /**
     * @Event("App\Event\EmailRegisterEvent")
     */
    public const EMAIL_REGISTER = 'user.email.register';

    /**
     * @Event("App\Event\EmailRegisterEvent")
     */
    public const EMAIL_RECOVER_PASSWORD = 'user.email.recover.password';
}