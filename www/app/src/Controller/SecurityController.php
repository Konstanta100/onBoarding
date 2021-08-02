<?php


namespace App\Controller;


use App\Dto\Request\ConfirmEmailRequest;
use App\Dto\Request\ConfirmUserRequest;
use App\Dto\Request\LoginRequest;
use App\Dto\Response\MessageResponse;
use App\Dto\Request\RegisterByEmailRequest;
use App\Service\EmailRegister;
use App\Service\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
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
     * @Route("/api/registerEmail", name="registerEmail", methods={"POST"})
     * @param RegisterByEmailRequest $registerRequest
     * @return JsonResponse
     */
    public function registerEmailAction(RegisterByEmailRequest $registerRequest): JsonResponse
    {
        $this->registerService->setStrategy($this->emailRegister);

        $registerResponse = $this->registerService->initiate($registerRequest);

        return $this->json(new MessageResponse($registerResponse->getMessage()), $registerResponse->getCode());
    }


    /**
     * @Route("/api/confirmEmail/{token}/user/{userId}", name="confirmEmail", methods={"GET"})
     * @param string $token
     * @param int $userId
     * @return JsonResponse
     */
    public function confirmEmailAction(string $token, int $userId): JsonResponse
    {
        $this->registerService->setStrategy($this->emailRegister);

        $confirmResponse = $this->registerService->confirm(new ConfirmUserRequest($userId, $token));

        return $this->json(new MessageResponse($confirmResponse->getMessage()), $confirmResponse->getCode());
    }

    /**
     * @Route("/api/sendEmailConfirm", name="sendEmailConfirm", methods={"POST"})
     * @param ConfirmEmailRequest $confirmEmailRequest
     * @return JsonResponse
     */
    public function sendEmailConfirmAction(ConfirmEmailRequest $confirmEmailRequest): JsonResponse
    {
        $this->registerService->setStrategy($this->emailRegister);

        $confirmEmailResponse = $this->registerService->confirmContact($confirmEmailRequest);

        return $this->json(new MessageResponse($confirmEmailResponse->getMessage()), $confirmEmailResponse->getCode());
    }

    /**
     * @Route("/api/login", name="login", methods={"POST"})
     * @param LoginRequest $loginRequest
     * @return JsonResponse
     */
    public function loginAction(LoginRequest $loginRequest): JsonResponse
    {
        $user = $this->getUser();

        return $this->json([
            'login' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
        ]);
    }
}