<?php


namespace App\Service;


use App\Dto\RegisterRequest;

interface RegisterStrategy
{
    /**
     * @param RegisterRequest $registerRequest
     */
    public function initiate(RegisterRequest $registerRequest);

    public function confirmContact();
}