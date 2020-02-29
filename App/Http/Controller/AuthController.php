<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\Exception\Http\NotFoundException;
use App\Exception\Model\AttributeNotExistsException;
use App\Exception\Validation\ValidationException;
use App\Http\Request\Request;
use App\Http\Routing\Redirector;
use App\Model\User;
use App\Service\UserService;

/**
 * Class AuthController
 * @package App\Http\Controller
 */
class AuthController
{
    public function registrationForm()
    {
        require_once BASE_PATH . '/views/auth/register.html';
    }

    /**
     * @param Request $request
     */
    public function registration(Request $request)
    {
        try {
            //@todo: validate password
            $postParams = $request->getPostParams();
            unset($postParams['password_confirm']);
            $user = new User($postParams);
            $userService = new UserService();
            $userService->register($user);
            (new Redirector($request))->redirectTo('home');
        } catch (\Exception $e) {
            var_dump($e);
        }
    }

    public function loginForm()
    {
        require_once BASE_PATH . '/views/auth/login.html';
    }

    /**
     * @param Request $request
     * @throws NotFoundException
     * @throws AttributeNotExistsException
     * @throws \ReflectionException
     */
    public function login(Request $request)
    {
        $params = $request->getPostParams();

        try {
            (new UserService())->login($params['login'], $params['password']);
            (new Redirector($request))->redirectTo('home');
        } catch (ValidationException $e) {
            (new Redirector($request))->redirectTo('login-form');
        }
    }
}
