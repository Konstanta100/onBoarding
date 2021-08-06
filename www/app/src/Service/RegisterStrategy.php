<?php


namespace App\Service;


use App\Dto\Request\ConfirmContactRequest;
use App\Dto\Request\ConfirmUserRequest;
use App\Dto\Request\RecoverPasswordRequest;
use App\Dto\Request\RegisterRequest;
use App\Dto\Response\RecoverResponse;
use App\Dto\Response\RegisterResponse;

interface RegisterStrategy
{
    /**
     * @param RegisterRequest $registerRequest
     */
    public function initiate(RegisterRequest $registerRequest): RegisterResponse;

    public function confirmUser(ConfirmUserRequest $confirmRequest): RegisterResponse;

    public function confirmContact(ConfirmContactRequest $confirmContactRequest): RegisterResponse;

    public function recoverPassword(RecoverPasswordRequest $recoverPasswordRequest): RecoverResponse;
}