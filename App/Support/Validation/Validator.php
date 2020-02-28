<?php
declare(strict_types=1);

namespace App\Support\Validation;

use App\Support\Validation\Rules\Base\RuleInterface;

/**
 * Class Validator
 * @package App\Support\Validation
 */
class Validator
{
    /** @var array */
    private $fields;

    /**
     * @var RuleInterface[]
     */
    private $rules;

    /** @var callable */
    private $callback;

    /** @var bool */
    private $is_valid = true;

    /** @var bool */
    private $is_processed = false;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * Validator constructor.
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param RuleInterface[] $rules
     * @return $this
     */
    public function setRules(array $rules): self
    {
        //@todo: check format of rules array
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
     * @param string $field
     * @param string $error
     */
    public function addError(string $field, string $error): void
    {
        $this->errors[$field][] = $error;
        $this->is_valid = false;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if ($this->is_processed) {
            return $this->is_valid;
        }

        $this->process();

        return $this->is_valid;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool|string
     */
    public function getFirstError()
    {
        return array_values($this->getErrors())[0][0] ?? false;
    }

    /**
     * @param string $field
     * @return RuleInterface|array
     */
    private function getRules(string $field): array
    {
        return $this->rules[$field] ?? [];
    }

    /**
     * Process validation rules and execute validation callback
     */
    private function process(): void
    {
        foreach ($this->fields as $field => $value) {
            /** @var RuleInterface $rule */
            foreach ($this->getRules($field) as $rule) {
                if (!$rule->validate($value, $field, $this->fields)) {
                    $this->addError($field, $rule->errorMessage());
                }
            }
        }

        if ($this->callback) {
            call_user_func($this->callback, $this);
        }

        $this->is_processed = true;
    }
}
