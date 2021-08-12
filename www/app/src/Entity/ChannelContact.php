<?php


namespace App\Entity;


class ChannelContact
{
    public const NONE = 0;

    public const EMAIL = 1;

    public const PHONE = 2;

    public const EMAIL_AND_PHONE = 3;

    static function getChannels(): array
    {
        return [
            self::EMAIL,
            self::EMAIL_AND_PHONE,
            self::EMAIL_AND_PHONE
        ];
    }

    static function getEmailChannels(): array
    {
        return [
            self::EMAIL,
            self::EMAIL_AND_PHONE
        ];
    }

    static function getPhoneChannels(): array
    {
        return [
            self::PHONE,
            self::EMAIL_AND_PHONE
        ];
    }
}