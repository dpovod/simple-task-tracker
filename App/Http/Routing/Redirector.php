<?php
declare(strict_types=1);

namespace App\Http\Routing;

use App\Exception\Http\NotFoundException;
use App\Http\Request\Request;

class Redirector
{
    /** @var RouteStorage */
    private $routeStorage;

    /**
     * Redirector constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->routeStorage = $request->getRouteStorage();
    }

    /**
     * @param string $routeName
     * @throws NotFoundException
     */
    public function redirectTo(string $routeName)
    {
        $route = $this->routeStorage->getRoute($routeName);

        if ($route === null) {
            throw new NotFoundException("Route '$routeName' not found.");
        }

        $uri = getenv('SITE_URI') . $route->getUri();
        header("Location: $uri");
        exit;
    }
}