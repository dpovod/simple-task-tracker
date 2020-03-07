<?php
declare(strict_types=1);

namespace App\Http\Routing;

use App\Http\Controller\AuthController;
use App\Http\Controller\HomeController;
use App\Http\Controller\IssueController;
use App\Http\Controller\UserController;

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
            // auth
            new Route('registration-form', 'GET', '/auth/register', AuthController::class, 'registrationForm'),
            new Route('registration', 'POST', '/auth/register', AuthController::class, 'registration'),
            new Route('login-form', 'GET', '/auth/login', AuthController::class, 'loginForm'),
            new Route('login', 'POST', '/auth/login', AuthController::class, 'login'),
            //issues
            new Route('my-issues', 'GET', '/issues/my', IssueController::class, 'myIssues'),
            new Route('assigned-to-me', 'GET', '/issues/assigned-to-me', IssueController::class, 'assignedToMe'),
            new Route('created-by-user', 'GET', '/issues/created-by/{id}', IssueController::class, 'createdByUser'),
            new Route('assigned-to-user', 'GET', '/issues/assigned-to/{id}', IssueController::class, 'assignedToUser'),
            new Route('create-issue-form', 'GET', '/issues/create', IssueController::class, 'createIssueForm'),
            new Route('create-issue', 'POST', '/issues/create', IssueController::class, 'createIssue'),
            new Route('edit-issue-form', 'GET', '/issues/edit/{id}', IssueController::class, 'editIssueForm'),
            new Route('edit-issue', 'POST', '/issues/edit/{id}', IssueController::class, 'editIssue'),
            new Route('show-issue', 'GET', '/issues/show/{id}', IssueController::class, 'showIssue'),
            //users
            new Route('users', 'GET', '/users', UserController::class, 'listUsers'),
            new Route('user', 'GET', '/users/{id}', UserController::class, 'showUser'),
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
