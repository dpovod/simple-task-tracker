<?php
declare(strict_types=1);

namespace App\Http\Controller;

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
}
