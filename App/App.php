<?php

namespace App;

use App\Http\Request\Request;
use App\Http\Routing\RouteRegistrar;
use App\Http\Routing\RouteResolver;
use App\Http\Routing\RouteStorage;
use App\Exception\Http\NotFoundException;
use App\Exception\Http\RouteAlreadyRegisteredException;

/**
 * Class App
 * @package App
 */
class App
{
    /**
     * @param Request $request
     * @throws NotFoundException
     * @throws RouteAlreadyRegisteredException
     */
    public function run(Request $request)
    {
        $routeStorage = new RouteStorage();
        $routeStorage->init();

        $request->setRouteStorage($routeStorage);

        $routeRegistrant = new RouteRegistrar();
        $routeRegistrant->registerRoutes($routeStorage->getRoutes());

        $routeResolver = new RouteResolver($routeStorage);
        $routeResolver->resolve($request);
    }
}
