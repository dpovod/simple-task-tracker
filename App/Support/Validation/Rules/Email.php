<?php
declare(strict_types=1);

namespace App\Support\Validation\Rules;

use App\Support\Validation\Rules\Base\Rule;

/**
 * Class Email
 * @package App\Support\Validation\Rules
 */
class Email extends Rule
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

        return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
