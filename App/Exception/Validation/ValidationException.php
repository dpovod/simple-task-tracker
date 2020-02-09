<?php
declare(strict_types=1);

namespace App\Exception\Validation;

use Exception;
use Throwable;

/**
 * Class ValidationException
 * @package App\Exception\Validation
 */
class ValidationException extends Exception
{
    public function __construct($message = "Data isn't valid.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
