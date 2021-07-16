<?php


namespace App\Service;


use App\Dto\RegisterByEmailRequest;
use App\Entity\User;

class RegisterService
{
    private UserService $userService;

    public function __construct(
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    public function sendEmail(RegisterByEmailRequest $registerRequest)
    {
    }


    /**
     * @param RegisterByEmailRequest $registerRequest
     */
    public function addUser(RegisterByEmailRequest $registerRequest): User
    {
        return new User();
//        if($this->userService->isUser($registerRequest)){
//            return;
//        }
//
//        return $this->userService->createUser();

    }
}