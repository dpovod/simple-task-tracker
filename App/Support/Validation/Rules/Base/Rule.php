<?php
declare(strict_types=1);

namespace App\Support\Validation\Rules\Base;

/**
 * Class Rule
 * @package App\Support\Validation\Rules\Base
 */
class Rule implements RuleInterface
{
    /** @var string */
    protected $field = '';

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return sprintf("Field '%s' is not valid.", $this->field);
    }

    /**
     * @param $value
     * @param string $field
     * @return bool
     */
    public function validate($value, string $field): bool
    {
        $this->field = $field;

        return true;
    }
}
