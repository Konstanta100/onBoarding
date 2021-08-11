<?php


namespace App\Service;


use App\Dto\Request\ConfirmContactRequest;
use App\Dto\Request\RegisterRequest;
use App\Dto\Response\DataResponse;

interface RegisterStrategy
{
    public function initiate(RegisterRequest $request): DataResponse;

    public function confirm(ConfirmContactRequest $request): DataResponse;

    public function recoverPassword(RegisterRequest $request): DataResponse;
}