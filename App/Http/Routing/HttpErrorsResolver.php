<?php
declare(strict_types=1);

namespace App\Http\Routing;

use Exception;

class HttpErrorsResolver
{
    private const HANDLED_ERROR_CODES = [400, 401, 404, 405, 500];

    /**
     * @param Exception $e
     * @throws Exception
     */
    public function handleErrorIfPossible(Exception $e)
    {
        if ($this->isHandlable($e)) {
            $code = $e->getCode();
            http_response_code($code);
            $path = BASE_PATH;
            require_once "$path/views/error-pages/{$code}.html";
            exit();
        }

        throw $e;
    }

    private function isHandlable(Exception $e)
    {
        return in_array($e->getCode(), self::HANDLED_ERROR_CODES, true);
    }
}
