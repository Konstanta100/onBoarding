<?php


namespace App\Service;


use App\Dto\Request\AcceptPasswordRequest;
use App\Dto\Request\ConfirmContactRequest;
use App\Dto\Request\ConfirmUserRequest;
use App\Dto\Request\RecoverPasswordRequest;
use App\Dto\Request\RegisterRequest;
use App\Dto\Response\DataResponse;

interface RegisterStrategy
{
    public function initiate(RegisterRequest $registerRequest): DataResponse;

    public function confirmUser(ConfirmUserRequest $confirmRequest): DataResponse;

    public function confirmContact(ConfirmContactRequest $confirmContactRequest): DataResponse;

    public function recoverPassword(RecoverPasswordRequest $recoverPasswordRequest): DataResponse;

    public function acceptPassword(AcceptPasswordRequest $acceptPasswordRequest): DataResponse;
}