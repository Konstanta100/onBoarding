<?php

declare(strict_types=1);

namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class RegisterByEmailRequest implements RegisterRequest
{
    /**
     * @Assert\Email(
     *     message = "The email {{ value }} is not a valid"
     * )
     * @Assert\NotBlank(
     *     message = "The email is not specified"
     * )
     * @Serializer\Expose()
     * @Serializer\Type("string")
     *
     * @var string
     */
    private string $email = '';

    /**
     * @Assert\NotBlank(
     *     message = "The password is not specified"
     * )
     * @Serializer\Expose()
     * @Serializer\Type("string")
     *
     * @var string
     */
    private string $password = '';

    /**
     * @return string
     */
    public function getContact(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}