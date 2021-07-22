<?php


namespace App\Dto;


class ValidationError
{
    private string $message;

    private string $property;

    private string $invalidValue;

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
     * @return string
     */
    public function getInvalidValue(): string
    {
        return $this->invalidValue;
    }

    /**
     * @param string $invalidValue
     * @return ValidationError
     */
    public function setInvalidValue(string $invalidValue): self
    {
        $this->invalidValue = $invalidValue;
        return $this;
    }
}