<?php
declare(strict_types=1);

namespace App\Http\Routing;

use App\Exception\Http\RouteAlreadyRegisteredException;

/**
 * Class RouteRegistrar
 * @package App\Http\Routing
 */
class RouteRegistrar
{
    /** @var Route[] */
    private $registeredRoutes = [];

    /**
     * @param Route $route
     * @throws RouteAlreadyRegisteredException
     */
    public function registerRoute(Route $route)
    {
        foreach ($this->registeredRoutes as $registeredRoute) {
            if ($route->getName() === $registeredRoute->getName()) {
                throw new RouteAlreadyRegisteredException("Route with name '{$route->getName()}' already registered.");
            }

            if ($route->getUri() === $registeredRoute->getUri()
                && $route->getMethod() === $registeredRoute->getMethod()) {
                throw new RouteAlreadyRegisteredException(
                    "Route for URL '{$route->getUri()}' (method {$route->getMethod()}) already registered."
                );
            }

            $this->registeredRoutes[] = $route;
        }
    }

    /**
     * @param Route[] $routes
     * @throws RouteAlreadyRegisteredException
     */
    public function registerRoutes(array $routes)
    {
        foreach ($routes as $route) {
            $this->registerRoute($route);
        }
    }
}
