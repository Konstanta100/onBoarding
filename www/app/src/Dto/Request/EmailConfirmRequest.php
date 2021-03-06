<?php

declare(strict_types=1);

namespace App\Dto\Request;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class EmailConfirmRequest implements ConfirmContactRequest
{
    /**
     * @Assert\NotBlank(
     *     message = "The userId is not specified"
     * )
     * @Serializer\Expose()
     * @Serializer\Type("string")
     *
     * @var string|null
     */
    private ?string $id = null;

    /**
     * @Assert\NotBlank(
     *     message = "The password is not specified"
     * )
     * @Serializer\Expose()
     * @Serializer\Type("string")
     *
     * @var string|null
     */
    private ?string $token = null;

    /**
     * @Assert\NotBlank(
     *     message = "The password is not specified"
     * )
     * @Serializer\Expose()
     * @Serializer\Type("string")
     *
     * @var string|null
     */
    private ?string $password = null;

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}