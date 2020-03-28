<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\Exception\Http\NotFoundException;
use App\Exception\Model\AttributeNotExistsException;
use App\Http\Controller\Base\BaseController;
use App\Http\Request\Request;
use App\Model\User;
use App\Repository\UserRepository;
use App\Support\Collection\PaginatedCollection;

/**
 * Class UserController
 * @package App\Http\Controller
 */
class UserController extends BaseController
{
    /**
     * @param Request $request
     * @return mixed
     * @throws AttributeNotExistsException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function listUsers(Request $request)
    {
        $users = PaginatedCollection::makeFromModel(
            User::class,
            $request->getCurrentRoute()->getFullUrl(),
            (int)$request->getGetParam('page', 1),
            5
        );

        return $this->renderView(BASE_PATH . '/views/users/list.phtml', [
            'users' => $users->getItems(),
            'pagination_links' => $users->getPaginationLinks(),
            'total_count' => $users->getTotalCount()
        ]);
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
