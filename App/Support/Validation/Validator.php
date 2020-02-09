<?php
declare(strict_types=1);

namespace App\Support\Validation;

/**
 * Class Validator
 * @package App\Support\Validation
 */
class Validator
{
    /**
     * @var array
     */
    private $rules;

    /** @var callable */
    private $callback;

    /** @var bool */
    private $is_valid = true;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param array $rules
     * @return $this
     */
    public function setRules(array $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function setCallback(callable $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * @param string $error
     */
    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->is_valid;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
