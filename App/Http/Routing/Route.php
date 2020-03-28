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
    private $url;

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
        $this->url = $this->prepareUrl($url);
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * @param string $url
     * @return string
     */
    private function prepareUrl(string $url)
    {
        if (strlen($url) > 1) {
            $url = rtrim('/' . $url, '/');
        }

        return str_replace('//', '/', $url);
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
     * @param array $withParams
     * @return string
     * @throws \Exception
     * @todo: throw more specific exceptions here
     */
    public function getUrlPath(array $withParams = []): string
    {
        if (!$withParams && $this->hasUrlParams()) {
            throw new \Exception('URL params should be specified.');
        }

        if ($withParams && !$this->hasUrlParams()) {
            throw new \Exception('There should not be parameters in the URL, but they were specified.');
        }

        if (!$withParams) {
            return $this->url;
        }

        $search = array_map(function ($paramName) {
            return '{' . $paramName . '}';
        }, array_keys($withParams));

        return str_replace($search, array_values($withParams), $this->url);
    }

    /**
     * @param array $withParams
     * @return string
     * @throws \Exception
     */
    public function getFullUrl(array $withParams = [])
    {
        return getenv('SITE_URI') . $this->getUrlPath($withParams);
    }

    public function getUrlRegex()
    {
        if (!$this->hasUrlParams()) {
            return "/$this->url/";
        }

        $varPattern = self::VAR_PATTERN;
        $regex = str_replace('/', '\/', preg_replace("/\{$varPattern}/", $varPattern, $this->url));

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
    private function hasUrlParams(): bool
    {
        $varPattern = self::VAR_PATTERN;

        return (bool)preg_match("/\{$varPattern}/", $this->url);
    }

    /**
     * @return array
     */
    public function getUrlParams(): array
    {
        if (!$this->hasUrlParams()) {
            return [];
        }

        $varPattern = self::VAR_PATTERN;
        preg_match_all("/\{($varPattern)}/U", $this->url, $matches);

        return isset($matches[1]) ? $matches[1] : [];
    }

    /**
     * @param string $url
     * @return bool
     * @throws \Exception
     */
    public function checkUrlMatch(string $url)
    {
        if (!$this->hasUrlParams()) {
            return $this->getUrlPath() === $this->prepareUrl($url);
        }

        $regex = $this->getUrlRegex();

        return (bool)preg_match($regex, $url);
    }

    /**
     * @param string $url
     * @return bool
     * @throws \Exception
     */
    public function mapParams(string $url)
    {
        if (!$this->checkUrlMatch($url)) {
            //todo: throw more specific exception
            throw new \Exception("URL doesn't match.");
        }

        if (!$this->hasUrlParams()) {
            return true;
        }

        $varPattern = self::VAR_PATTERN;
        $regex = str_replace('/', '\/', preg_replace("/\{$varPattern}/", "($varPattern)", $this->url));

        preg_match_all("/$regex/", $url, $matches);

        if (empty($matches)) {
            return false;
        }

        unset($matches[0]);
        $matches = array_values($matches);
        $paramsNames = $this->getUrlParams();

        foreach ($matches as $key => $match) {
            $this->params[$paramsNames[$key]] = $match[0];
        }

        return true;
    }

    /**
     * @param string $paramName
     * @param null $default
     * @return mixed|null
     */
    public function getParam(string $paramName, $default = null)
    {
        return $this->params[$paramName] ?? $default;
    }
}
