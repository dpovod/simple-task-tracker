<?php
declare(strict_types=1);

namespace App\Exception\Http;

use Exception;
use Throwable;

/**
 * Class RouteAlreadyRegisteredException
 * @package App\Exception\Http
 */
class RouteAlreadyRegisteredException extends Exception
{
    /**
     * RouteAlreadyRegisteredException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Route already registered.", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
