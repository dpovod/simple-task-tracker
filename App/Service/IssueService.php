<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\Model\FieldTypeNotAllowedException;
use App\Exception\Validation\ValidationException;
use App\Model\Issue;
use App\Repository\IssueRepository;
use App\Support\Validation\Rules\Number;
use App\Support\Validation\Rules\Required;
use App\Support\Validation\Rules\StringLength;
use App\Support\Validation\Validator;
use ReflectionException;

/**
 * Class IssueService
 * @package App\Service
 */
class IssueService
{
    /**
     * @param Issue $issue
     * @return bool
     * @throws FieldTypeNotAllowedException
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function create(Issue $issue)
    {
        $validator = new Validator($issue->getAttributes());

        if (!$issue->has('status')) {
            $issue->set('status', Issue::STATUS_NEW);
        }

        $validator->setRules([
            'title' => [new Required(), new StringLength(10)],
            'description' => [new Required(), new StringLength(10, 1000)],
            'assigned_to_id' => [new Required(), new Number()],
        ]);

        if (!$validator->isValid()) {
            throw new ValidationException($validator->getFirstError());
        }

        $issue->set('author_id', UserService::authUserId());

        return (new IssueRepository())->insert($issue);
    }
}
