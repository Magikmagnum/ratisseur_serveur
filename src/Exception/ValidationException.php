<?php

namespace App\Exception;

class ValidationException extends \Exception
{
    protected array $validationErrors;

    public function __construct(array $validationErrors, $code = 0, \Exception $previous = null)
    {
        $this->validationErrors = $validationErrors;
        parent::__construct('Validation failed', $code, $previous);
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}
