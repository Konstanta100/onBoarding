<?php

namespace App\Exception;

use App\Dto\ValidationError;
use Throwable;

class ValidationException extends \Exception
{
    /**
     * Validation error messages.
     *
     * @var ValidationError[]
     */
    protected array $errors = [];

    /**
     * Constructor.
     *
     * @param string|null $message
     * @param int $code
     * @param ValidationError[] $errors
     * @param Throwable|null $previous
     */
    public function __construct(array $errors = [], ?string $message = 'invalid parameters', int $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}