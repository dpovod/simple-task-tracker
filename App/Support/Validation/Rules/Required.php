<?php
declare(strict_types=1);

namespace App\Support\Validation\Rules;

use App\Support\Validation\Rules\Base\Rule;

/**
 * Class Required
 * @package App\Support\Validation\Rules
 */
class Required extends Rule
{
    /**
     * @param $value
     * @param string $field
     * @param array $fields
     * @return bool
     */
    public function validate($value, string $field, array $fields = []): bool
    {
        parent::validate($value, $field);

        return array_key_exists($field, $fields);
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return sprintf("Field '%s' is required.", $this->field);
    }
}
