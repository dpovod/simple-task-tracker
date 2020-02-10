<?php
declare(strict_types=1);

namespace App\Support\Validation\Rules\Base;

/**
 * Interface RuleInterface
 * @package App\Support\Validation\Rules\Base
 */
interface RuleInterface
{
    /**
     * @return string
     */
    public function errorMessage(): string;

    /**
     * @param $value
     * @param string $field
     * @return bool
     */
    public function validate($value, string $field): bool;
}
