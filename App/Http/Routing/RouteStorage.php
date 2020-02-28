<?php
declare(strict_types=1);

namespace App\Http\Routing;

use App\Http\Controller\AuthController;
use App\Http\Controller\HomeController;

/**
 * Class RouteStorage
 * @package App\Http\Routing
 */
class RouteStorage
{
    /** @var Route[] */
    private $routes;

    public function init()
    {
        $this->routes = [
            new Route('home', 'GET', '/', HomeController::class, 'index'),
            new Route('registration-form', 'GET', '/auth/register', AuthController::class, 'registrationForm'),
            new Route('registration', 'POST', '/auth/register', AuthController::class, 'registration'),
        ];
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param string $name
     * @return Route|null
     */
    public function getRoute(string $name): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->getName() === $name) {
                return $route;
            }
        }

        return null;
    }
}
