<?php
declare(strict_types=1);

namespace App\Support\Validation\Rules;

use App\Support\Validation\Rules\Base\Rule;

/**
 * Class Number
 * @package App\Support\Validation\Rules
 */
class Number extends Rule
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

        return is_numeric($value);
    }
}
