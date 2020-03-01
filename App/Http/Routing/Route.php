<?php
declare(strict_types=1);

namespace App\Http\Routing;

/**
 * Class Route
 * @package App\Http\Routing
 */
class Route
{
    private const VAR_PATTERN = '[0-9a-zA-Z-_]+';

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

    /** @var array */
    private $params;

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

    public function getUriRegex()
    {
        if (!$this->hasUriParams()) {
            return "/$this->uri/";
        }

        $varPattern = self::VAR_PATTERN;
        $regex = str_replace('/', '\/', preg_replace("/\{$varPattern}/", $varPattern, $this->uri));

        return "/$regex/";
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

    /**
     * @return bool
     */
    private function hasUriParams(): bool
    {
        $varPattern = self::VAR_PATTERN;

        return (bool)preg_match("/\{$varPattern}/", $this->uri);
    }

    /**
     * @return array
     */
    public function getUriParams(): array
    {
        if (!$this->hasUriParams()) {
            return [];
        }

        $varPattern = self::VAR_PATTERN;
        preg_match_all("/\{($varPattern)}/U", $this->uri, $matches);

        return isset($matches[1]) ? $matches[1] : [];
    }

    /**
     * @param string $uri
     * @return bool
     */
    public function checkUriMatch(string $uri)
    {
        if (!$this->hasUriParams()) {
            return $this->getUri() === $this->prepareUri($uri);
        }

        $regex = $this->getUriRegex();

        return (bool)preg_match($regex, $uri);
    }

    /**
     * @param string $uri
     * @return bool
     * @throws \Exception
     */
    public function mapParams(string $uri)
    {
        if (!$this->checkUriMatch($uri)) {
            //todo: throw more specific exception
            throw new \Exception("Uri doesn't match.");
        }

        if (!$this->hasUriParams()) {
            return true;
        }

        $varPattern = self::VAR_PATTERN;
        $regex = str_replace('/', '\/', preg_replace("/\{$varPattern}/", "($varPattern)", $this->uri));

        preg_match_all("/$regex/", $uri, $matches);

        if (empty($matches)) {
            return false;
        }

        unset($matches[0]);
        $matches = array_values($matches);
        $paramsNames = $this->getUriParams();

        foreach ($matches as $key => $match) {
            $this->params[$paramsNames[$key]] = $match[0];
        }

        return true;
    }

    /**
     * @param string $paramName
     * @return mixed|null
     */
    public function getParam(string $paramName)
    {
        return $this->params[$paramName] ?? null;
    }
}
