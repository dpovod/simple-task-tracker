<?php
declare(strict_types=1);

namespace App\Support\Validation\Rules\Base;

/**
 * Class Number
 * @package App\Support\Validation\Rules\Base
 */
class Number extends Rule
{
    /**
     * @param $value
     * @param string $field
     * @return bool
     */
    public function validate($value, string $field): bool
    {
        parent::validate($value, $field);

        return is_numeric($value);
    }
}
