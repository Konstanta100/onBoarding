<?php

declare(strict_types=1);

namespace App\Dto\Request;


interface RegisterRequest
{
    public function getContact(): ?string;
}