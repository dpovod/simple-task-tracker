<?php
declare(strict_types=1);

namespace App\Support\Validation\Rules\Base;

/**
 * Class Email
 * @package App\Support\Validation\Rules\Base
 */
class Email extends Rule
{
    /**
     * @param $value
     * @param string $field
     * @return bool
     */
    public function validate($value, string $field): bool
    {
        parent::validate($value, $field);

        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
