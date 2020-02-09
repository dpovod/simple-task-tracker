<?php
declare(strict_types=1);

namespace App\Exception\Model;

use Throwable;

/**
 * Class FieldTypeNotAllowedException
 * @package App\Exception\Model
 */
class FieldTypeNotAllowedException extends \Exception
{
    /**
     * FieldTypeNotAllowedException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Unknown Field Type", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
