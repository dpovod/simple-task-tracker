<?php
declare(strict_types=1);

namespace App\Exception\Model;

use Exception;
use Throwable;

/**
 * Class AttributeNotExistsException
 * @package App\Exception\Model
 */
class AttributeNotExistsException extends Exception
{
    /**
     * AttributeNotExistsException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Attribute not exists", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
