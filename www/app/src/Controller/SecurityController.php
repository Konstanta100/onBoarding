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
    /**
     * @var RegisterService
     */
    private RegisterService $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    /**
     * @Route("/api/registerByEmail", name="registerByEmail", methods={"POST"})
     * @param RegisterByEmailRequest $registerRequest
     * @return JsonResponse
     */
    public function registerByEmailAction(RegisterByEmailRequest $registerRequest): JsonResponse
    {
        $this->registerService->setStrategy(new EmailRegister());

        $this->registerService->initiate($registerRequest);

        if($user){
            return new JsonResponse($user->getEmail());
        }

        return new JsonResponse();
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
        //        $result = $this->registerService->sendEmail($registerRequest);
        return $this->json(null);
    }
}