<?php


namespace App\Event;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

abstract class EmailEvent extends Event
{
    private User $user;

    protected string $subject = '';

    protected string $textLink = '';

    protected string $template;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $subject
     * @return void
     */
    public function convertSubject(string $subject): void
    {
        $this->subject = "=?utf-8?B?" . base64_encode($subject) . "?=";
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getTextLink(): string
    {
        return $this->textLink;
    }

    abstract protected function createTemplate(): void;
}