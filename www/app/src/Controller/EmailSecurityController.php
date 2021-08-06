<?php


namespace App\Controller;


use App\Dto\Request\AcceptPasswordRequest;
use App\Dto\Request\ConfirmEmailRequest;
use App\Dto\Request\ConfirmUserRequest;
use App\Dto\Request\RecoverPasswordRequest;
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
     * @Route("/confirmEmail/{token}/user/{userId}", name="confirmEmail", methods={"GET"})
     * @param string $token
     * @param int $userId
     * @return JsonResponse
     */
    public function confirmEmailAction(string $token, int $userId): JsonResponse
    {
        $this->registerService->setStrategy($this->emailRegister);

        $confirmResponse = $this->registerService->confirm(new ConfirmUserRequest($userId, $token));

        return $this->json($confirmResponse, $confirmResponse->getCode());
    }

    /**
     * @Route("/sendEmailConfirm", name="sendEmailConfirm", methods={"POST"})
     * @param ConfirmEmailRequest $confirmEmailRequest
     * @return JsonResponse
     */
    public function sendEmailConfirmAction(ConfirmEmailRequest $confirmEmailRequest): JsonResponse
    {
        $this->registerService->setStrategy($this->emailRegister);

        $confirmEmailResponse = $this->registerService->confirmContact($confirmEmailRequest);

        return $this->json($confirmEmailResponse, $confirmEmailResponse->getCode());
    }

    /**
     * @Route("/recoverPassword", name="recoverPassword", methods={"POST"})
     * @param RecoverPasswordRequest $recoverPasswordRequest
     * @return JsonResponse
     */
    public function recoverPasswordAction(RecoverPasswordRequest $recoverPasswordRequest): JsonResponse
    {
        $this->registerService->setStrategy($this->emailRegister);

        $recoverPasswordResponse = $this->registerService->recoverPassword($recoverPasswordRequest);

        return $this->json($recoverPasswordResponse, $recoverPasswordResponse->getCode());
    }

    /**
     * @Route("/acceptPassword", name="acceptPassword", methods={"POST"})
     * @param AcceptPasswordRequest $acceptPasswordRequest
     * @return JsonResponse
     */
    public function acceptPasswordEmail(AcceptPasswordRequest $acceptPasswordRequest): JsonResponse
    {
        $this->registerService->setStrategy($this->emailRegister);

        $acceptPasswordResponse = $this->registerService->acceptPassword($acceptPasswordRequest);

        return $this->json($acceptPasswordResponse, $acceptPasswordResponse->getCode());
    }
}