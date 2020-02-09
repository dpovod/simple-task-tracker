<?php
declare(strict_types=1);

namespace App\Http\Routing;

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
        ];
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
