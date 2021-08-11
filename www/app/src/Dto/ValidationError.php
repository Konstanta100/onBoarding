<?php

declare(strict_types=1);

namespace App\Dto;


class ValidationError
{
    private string $message;

    private string $property;

    /**
     * @var mixed
     */
    private $invalidValue;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return ValidationError
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @param string $property
     * @return ValidationError
     */
    public function setProperty(string $property): self
    {
        $this->property = $property;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInvalidValue()
    {
        return $this->invalidValue;
    }

    /**
     * @param mixed $invalidValue
     * @return ValidationError
     */
    public function setInvalidValue($invalidValue): self
    {
        $this->invalidValue = $invalidValue;
        return $this;
    }
}