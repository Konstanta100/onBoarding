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
    public function initiate(RegisterRequest $request): DataResponse;

    public function confirmUser(ConfirmUserRequest $request): DataResponse;

    public function confirmContact(ConfirmContactRequest $request): DataResponse;

    public function recoverPassword(RecoverPasswordRequest $request): DataResponse;

    public function acceptPassword(AcceptPasswordRequest $request): DataResponse;
}