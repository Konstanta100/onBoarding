<?php


namespace App\Dto\Request;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class EmailAcceptPasswordRequest implements AcceptPasswordRequest
{
    /**
     * @Assert\NotBlank(
     *     message = "The userId is not specified"
     * )
     * @Serializer\Expose()
     * @Serializer\Type("int")
     *
     * @var int
     */
    private int $userId;


    /**
     * @Assert\NotBlank(
     *     message = "The password is not specified"
     * )
     * @Serializer\Expose()
     * @Serializer\Type("string")
     *
     * @var string
     */
    private string $token;

    /**
     * @Assert\NotBlank(
     *     message = "The password is not specified"
     * )
     * @Serializer\Expose()
     * @Serializer\Type("string")
     *
     * @var string
     */
    private string $password;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}