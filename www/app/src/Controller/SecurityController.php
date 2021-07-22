<?php


namespace App\Controller;


use App\Dto\LoginRequest;
use App\Dto\RegisterByEmailRequest;
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
     * @Route("/api/registerByEmail", name="registerByEmail", methods={"POST"})
     * @param RegisterByEmailRequest $registerRequest
     * @return JsonResponse
     */
    public function registerByEmailAction(RegisterByEmailRequest $registerRequest): JsonResponse
    {
        $this->registerService->setStrategy($this->emailRegister);

        $this->registerService->initiate($registerRequest);

        return new JsonResponse(['message' => "The letter was sent to the email: {$registerRequest->getContact()}"]);
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

    /**
     * @Route("/api/verifyEmail", name="verifyEmail", methods={"POST"})
     * @return JsonResponse
     */
    public function verifyEmailAction(): JsonResponse
    {
        return $this->json('test');
    }
}