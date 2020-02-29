<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\Exception\Http\NotFoundException;
use App\Http\Request\Request;
use App\Http\Routing\Redirector;
use App\Service\UserService;

/**
 * Class HomeController
 * @package App\Http\Controller
 */
class HomeController
{
    /**
     * @param Request $request
     * @throws NotFoundException
     */
    public function index(Request $request)
    {
        $redirector = new Redirector($request);

        if (UserService::authUserId()) {
            $redirector->redirectTo('my-issues');
        } else {
            $redirector->redirectTo('login-form');
        }
    }
}
