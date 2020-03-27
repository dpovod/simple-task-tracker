<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\Exception\Http\NotFoundException;
use App\Exception\Model\AttributeNotExistsException;
use App\Http\Controller\Base\BaseController;
use App\Http\Request\Request;
use App\Repository\UserRepository;

/**
 * Class UserController
 * @package App\Http\Controller
 */
class UserController extends BaseController
{
    /**
     * @throws \ReflectionException
     * @throws AttributeNotExistsException
     */
    public function listUsers()
    {
        //@todo: pagination
        $users = (new UserRepository())->getList();
        return $this->renderView(BASE_PATH . '/views/users/list.phtml', compact('users'));
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws AttributeNotExistsException
     * @throws NotFoundException
     * @throws \ReflectionException
     */
    public function showUser(Request $request)
    {
        $id = $request->getCurrentRoute()->getParam('id');
        $user = (new UserRepository())->findFirstOrFail(['id' => $id]);

        return $this->renderView(BASE_PATH . '/views/users/show.phtml', compact('user'));
    }
}
