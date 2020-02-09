<?php
declare(strict_types=1);

namespace App\Http\Request;

use App\Exception\Http\MethodNotAllowedException;

/**
 * Class Request
 * @package App\Http\Request
 */
class Request
{
    public const METHOD_GET = 'GET';

    public const METHOD_POST = 'POST';

    private const ALLOWED_METHODS = [
        self::METHOD_GET,
        self::METHOD_POST,
    ];

    private $uri;

    private $method;

    /**
     * Request constructor.
     * @param string $uri
     * @param string $method
     * @throws MethodNotAllowedException
     */
    public function __construct(string $uri, string $method)
    {
        if (!$this->isMethodAllowed($method)) {
            throw new MethodNotAllowedException();
        }

        $this->uri = $uri;
        $this->method = $method;
    }

    /**
     * @param array $globalServer
     * @return Request
     * @throws MethodNotAllowedException
     */
    public static function makeFromGlobalRequestVariables(array $globalServer): self
    {
        $method = $globalServer['REQUEST_METHOD'];
        $uri = $globalServer['REQUEST_URI'];

        return new self($uri, $method);
    }

    /**
     * @param string $method
     * @return bool
     */
    private function isMethodAllowed(string $method): bool
    {
        return in_array($method, self::ALLOWED_METHODS, true);
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
}
