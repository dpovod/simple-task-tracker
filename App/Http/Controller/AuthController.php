<?php
declare(strict_types=1);

namespace App\Http\Controller;

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
        require_once BASE_PATH . '/html/auth/register.html';
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
}
