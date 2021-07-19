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
     * @Assert\NotBlank(
     *     message = "The email {{value}} is not specified."
     * )
     * @Serializer\Expose()
     * @Serializer\Type("string")
     *
     * @var string
     */
    private string $email = '';

    /**
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