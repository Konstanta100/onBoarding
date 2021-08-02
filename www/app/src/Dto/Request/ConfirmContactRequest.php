<?php

declare(strict_types=1);

namespace App\Dto\Request;


interface ConfirmContactRequest
{
    public function getContact(): string;
}