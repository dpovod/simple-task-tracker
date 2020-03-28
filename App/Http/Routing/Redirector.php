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
     * @param array $withParams
     * @throws NotFoundException
     * @throws \Exception
     */
    public function redirectTo(string $routeName, array $withParams = [])
    {
        $route = $this->routeStorage->getRoute($routeName);

        if ($route === null) {
            throw new NotFoundException("Route '$routeName' not found.");
        }

        $url = $route->getFullUrl($withParams);
        header("Location: $url");
        exit;
    }
}
