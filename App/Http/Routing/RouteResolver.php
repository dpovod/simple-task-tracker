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
     */
    public function resolve(Request $request)
    {
        foreach ($this->routeStorage->getRoutes() as $route) {
            if ($request->getUri() === $route->getUri() && $request->getMethod() === $route->getMethod()) {
                return call_user_func([$route->getController(), $route->getAction()]);
            }
        }

        throw new NotFoundException();
    }
}
