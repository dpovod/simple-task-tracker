<?php
declare(strict_types=1);

namespace App\Exception\Http;

use Exception;
use Throwable;

/**
 * Class MethodNotAllowedException
 * @package App\Exception\Http
 */
class MethodNotAllowedException extends Exception
{
    /**
     * RouteAlreadyRegisteredException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Method not allowed.", $code = 405, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
