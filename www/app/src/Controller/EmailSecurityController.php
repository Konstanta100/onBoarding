<?php

declare(strict_types=1);

namespace App\Controller;


use App\Dto\Request\ContactConfirmRequest;
use App\Dto\Request\EmailConfirmRequest;
use App\Dto\Request\RegisterByEmailRequest;
use App\Service\EmailRegister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class EmailSecurityController extends AbstractController
{
    private EmailRegister $emailRegister;

    public function __construct(EmailRegister $emailRegister)
    {
        $this->emailRegister = $emailRegister;
    }

    /**
     * @Route("/registerEmail", name="registerEmail", methods={"POST"})
     * @param RegisterByEmailRequest $request
     * @return JsonResponse
     */
    public function registerEmailAction(RegisterByEmailRequest $request): JsonResponse
    {
        $response = $this->emailRegister->initiate($request);

        return $this->json($response, $response->getCode());
    }

    /**
     * @Route("/confirmEmail/token={token}", name="confirmEmail", methods={"POST"}, requirements={"token"="\d{32}"}))
     * @param string $token
     * @return JsonResponse
     */
    public function confirmEmailAction(string $token): JsonResponse
    {
        $request = new EmailConfirmRequest($token);
        $response = $this->emailRegister->confirm($request);

        return $this->json($response, $response->getCode());
    }

    /**
     * @Route("/recoverPassword", name="recoverPassword", methods={"POST"})
     * @param EmailInfoRequest $request
     * @return JsonResponse
     */
    public function recoverPasswordAction(EmailInfoRequest $request): JsonResponse
    {
        $response = $this->emailRegister->recoverPassword($request);

        return $this->json($response, $response->getCode());
    }

    /**
     * @Route("/confirmPassword", name="confirmPassword", methods={"POST"})
     * @param EmailInfoRequest $request
     * @return JsonResponse
     */
    public function confirmPasswordAction( $request): JsonResponse
    {
        $response = $this->emailRegister->confirmPassword($request);

        return $this->json($response, $response->getCode());
    }
}