<?php
declare(strict_types=1);

namespace App\Support\Validation\Rules\Base;

/**
 * Class StringLength
 * @package App\Support\Validation\Rules\Base
 */
class StringLength extends Rule
{
    /** @var int */
    private $min_length;

    /** @var int */
    private $max_length;

    /**
     * String constructor.
     * @param int $minLength
     * @param int $maxLength
     */
    public function __construct(int $minLength = 0, int $maxLength = -1)
    {
        $this->min_length = $minLength;
        $this->max_length = $maxLength;
    }

    /**
     * @param $value
     * @param string $field
     * @return bool
     */
    public function validate($value, string $field): bool
    {
        parent::validate($value, $field);
        $isString = is_string($value);

        if (!$isString) {
            return false;
        }

        $length = mb_strlen($value);

        if ($this->max_length !== -1) {
            return $length <= $this->max_length && $length >= $this->min_length;
        }

        return $length >= $this->min_length;
    }

    /**
     * @return string
     * @todo: add logic for different cases
     */
    public function errorMessage(): string
    {
        return sprintf(
            "Field '%s' must be a string with a length of %n to %n characters.",
            $this->field,
            $this->min_length,
            $this->max_length
        );
    }
}
