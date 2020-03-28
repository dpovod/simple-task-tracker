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
    private $uri_path;

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
     * @param string $uriPath
     * @param string $method
     * @param array $getParams
     * @param array $postParams
     * @throws MethodNotAllowedException
     */
    public function __construct(string $uriPath, string $method, array $getParams = [], array $postParams = [])
    {
        if (!$this->isMethodAllowed($method)) {
            throw new MethodNotAllowedException();
        }

        $this->uri_path = $uriPath;
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
        $uriPath = parse_url($globalServer['REQUEST_URI'], PHP_URL_PATH);

        return new self($uriPath, $method, $_GET, $_POST);
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
    public function getUriPath(): string
    {
        return $this->uri_path;
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
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function getGetParam(string $key, $default = null)
    {
        return $this->getParams[$key] ?? $default;
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
        $currentRoute->mapParams($this->getUriPath());
        $this->currentRoute = $currentRoute;
    }
}
