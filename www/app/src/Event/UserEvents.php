<?php


namespace App\Event;


final class UserEvents
{
    /**
     * @Event("App\Event\EmailRegisterEvent")
     */
    public const EMAIL_REGISTER = 'user.email.register';
}