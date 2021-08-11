<?php

declare(strict_types=1);

namespace App\Controller;


use App\Dto\Request\ConfirmContactRequest;
use App\Dto\Request\RegisterByEmailRequest;
use App\Service\EmailRegister;
use App\Service\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class EmailSecurityController extends AbstractController
{
    private RegisterService $registerService;

    private EmailRegister $emailRegister;

    public function __construct(
        RegisterService $registerService,
        EmailRegister $emailRegister
    )
    {
        $this->registerService = $registerService;
        $this->emailRegister = $emailRegister;
    }

    /**
     * @Route("/registerEmail", name="registerEmail", methods={"POST"})
     * @param RegisterByEmailRequest $registerRequest
     * @return JsonResponse
     */
    public function registerEmailAction(RegisterByEmailRequest $registerRequest): JsonResponse
    {
        $this->registerService->setStrategy($this->emailRegister);

        $registerResponse = $this->registerService->initiate($registerRequest);

        return $this->json($registerResponse, $registerResponse->getCode());
    }

    /**
     * @Route("/confirmEmail", name="confirmEmail", methods={"POST"})
     * @param ConfirmContactRequest $request
     * @return JsonResponse
     */
    public function confirmEmailAction(ConfirmContactRequest $request): JsonResponse
    {
        $this->registerService->setStrategy($this->emailRegister);

        $confirmResponse = $this->registerService->confirm($request);

        return $this->json($confirmResponse, $confirmResponse->getCode());
    }

    /**
     * @Route("/recoverPassword", name="recoverPassword", methods={"POST"})
     * @param RegisterByEmailRequest $recoverPasswordRequest
     * @return JsonResponse
     */
    public function recoverPasswordAction(RegisterByEmailRequest $recoverPasswordRequest): JsonResponse
    {
        $this->registerService->setStrategy($this->emailRegister);

        $recoverPasswordResponse = $this->registerService->recoverPassword($recoverPasswordRequest);

        return $this->json($recoverPasswordResponse, $recoverPasswordResponse->getCode());
    }
}