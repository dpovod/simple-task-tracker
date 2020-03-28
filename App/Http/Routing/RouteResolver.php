<?php
declare(strict_types=1);

namespace App\Http\Routing;

use App\Exception\Http\NotFoundException;
use App\Http\Request\Request;

/**
 * Class RouteResolver
 * @package App\Http\Routing
 */
class RouteResolver
{
    /** @var RouteStorage */
    private $routeStorage;

    /**
     * RouteResolver constructor.
     * @param RouteStorage $routeStorage
     */
    public function __construct(RouteStorage $routeStorage)
    {
        $this->routeStorage = $routeStorage;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws NotFoundException
     * @throws \Exception
     */
    public function resolve(Request $request)
    {
        foreach ($this->routeStorage->getRoutes() as $route) {
            if ($route->checkUrlMatch($request->getUriPath()) && $route->getMethod() === $request->getMethod()) {
                if (!method_exists($route->getController(), $route->getAction())) {
                    throw new NotFoundException();
                }

                $request->setCurrentRoute($route);
                $className = $route->getController();
                $controllerInstance = new $className;

                return $controllerInstance->{$route->getAction()}($request);
            }
        }

        throw new NotFoundException();
    }
}
