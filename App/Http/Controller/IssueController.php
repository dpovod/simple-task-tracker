<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\Exception\Http\NotFoundException;
use App\Exception\Model\AttributeNotExistsException;
use App\Exception\Model\FieldTypeNotAllowedException;
use App\Exception\Validation\ValidationException;
use App\Http\Request\Request;
use App\Http\Routing\Redirector;
use App\Model\Issue;
use App\Repository\IssueRepository;
use App\Repository\UserRepository;
use App\Service\IssueService;
use App\Service\UserService;

/**
 * Class TaskController
 * @package App\Http\Controller
 */
class IssueController
{
    /**
     * @throws \ReflectionException
     * @throws AttributeNotExistsException
     */
    public function myIssues()
    {
        $users = (new UserRepository())->getList();
        //@todo: pagination
        $issues = (new IssueRepository())->findWhere(['author_id' => UserService::authUserId()]);
        $contentView = BASE_PATH . '/views/issues/my-issues.phtml';

        require_once BASE_PATH . '/views/layouts/master.phtml';
    }

    /**
     * @throws \ReflectionException
     * @throws AttributeNotExistsException
     */
    public function assignedToMe()
    {
        $users = (new UserRepository())->getList();
        //@todo: pagination
        $issues = (new IssueRepository())->findWhere(['assigned_to_id' => UserService::authUserId()]);
        $contentView = BASE_PATH . '/views/issues/assigned-to-me.phtml';

        require_once BASE_PATH . '/views/layouts/master.phtml';
    }

    /**
     * @throws \ReflectionException
     * @throws AttributeNotExistsException
     */
    public function createIssueForm()
    {
        $users = (new UserRepository())->getList();
        $contentView = BASE_PATH . '/views/issues/create.phtml';

        require_once BASE_PATH . '/views/layouts/master.phtml';
    }

    /**
     * @param Request $request
     * @throws FieldTypeNotAllowedException
     * @throws \ReflectionException
     * @throws NotFoundException
     */
    public function createIssue(Request $request)
    {
        $redirector = new Redirector($request);
        $issueService = new IssueService();

        try {
            if ($issueService->create(new Issue($request->getPostParams()))) {
                $redirector->redirectTo('my-issues');
            }
        } catch (ValidationException $e) {
            //@todo: show error
        }

        $redirector->redirectTo('create-issue-form');
    }
}
