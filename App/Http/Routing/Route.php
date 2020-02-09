<?php
declare(strict_types=1);

namespace App\Http\Routing;

/**
 * Class Route
 * @package App\Http\Routing
 */
class Route
{
    /** @var string */
    private $name;

    /** @var string */
    private $method;

    /** @var string */
    private $uri;

    /** @var string */
    private $controller;

    /** @var string */
    private $action;

    /**
     * Route constructor.
     * @param string $name
     * @param string $method
     * @param string $url
     * @param string $controller
     * @param string $action
     */
    public function __construct(string $name, string $method, string $url, string $controller, string $action)
    {
        $this->name = $name;
        $this->method = $method;
        $this->uri = $this->prepareUri($url);
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * @param string $uri
     * @return string
     */
    private function prepareUri(string $uri)
    {
        if (strlen($uri) > 1) {
            $uri = rtrim('/' . $uri, '/');
        }

        return str_replace('//', '/', $uri);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
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
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
