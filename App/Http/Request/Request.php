<?php
declare(strict_types=1);

namespace App\Http\Request;

use App\Exception\Http\MethodNotAllowedException;
use App\Http\Routing\Route;
use App\Http\Routing\RouteStorage;

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

    /** @var string */
    private $uri;

    /** @var string */
    private $method;

    /** @var array */
    private $getParams;

    /** @var array */
    private $postParams;

    /** @var RouteStorage */
    private $routeStorage;

    /** @var Route */
    private $currentRoute;

    /**
     * Request constructor.
     * @param string $uri
     * @param string $method
     * @param array $getParams
     * @param array $postParams
     * @throws MethodNotAllowedException
     */
    public function __construct(string $uri, string $method, array $getParams = [], array $postParams = [])
    {
        if (!$this->isMethodAllowed($method)) {
            throw new MethodNotAllowedException();
        }

        $this->uri = $uri;
        $this->method = $method;
        $this->getParams = $getParams;
        $this->postParams = $postParams;
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

        return new self($uri, $method, $_GET, $_POST);
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

    /**
     * @return array
     */
    public function getGetParams(): array
    {
        return $this->getParams;
    }

    /**
     * @return array
     */
    public function getPostParams(): array
    {
        return $this->postParams;
    }

    /**
     * @return RouteStorage
     */
    public function getRouteStorage(): RouteStorage
    {
        return $this->routeStorage;
    }

    /**
     * @param RouteStorage $routeStorage
     */
    public function setRouteStorage(RouteStorage $routeStorage): void
    {
        $this->routeStorage = $routeStorage;
    }

    /**
     * @return Route
     */
    public function getCurrentRoute(): Route
    {
        return $this->currentRoute;
    }

    /**
     * @param Route $currentRoute
     * @throws \Exception
     */
    public function setCurrentRoute(Route $currentRoute): void
    {
        $currentRoute->mapParams($this->getUri());
        $this->currentRoute = $currentRoute;
    }
}
