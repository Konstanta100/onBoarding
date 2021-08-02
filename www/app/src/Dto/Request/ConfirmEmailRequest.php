<?php

declare(strict_types=1);

namespace App\Dto\Request;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class ConfirmEmailRequest implements ConfirmContactRequest
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
     * @return string
     */
    public function getContact(): string
    {
        return $this->email;
    }
}