<?php
declare(strict_types=1);

namespace App\Exception\Http;

use Exception;
use Throwable;

/**
 * Class NotFoundException
 * @package App\Exception\Http
 */
class NotFoundException extends Exception
{
    /**
     * RouteAlreadyRegisteredException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Not Found.", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
