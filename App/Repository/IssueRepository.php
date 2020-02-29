<?php
declare(strict_types=1);

namespace App\Repository;

use App\Model\Issue;
use App\Repository\Base\BaseRepository;

/**
 * Class IssueRepository
 * @package App\Repository
 */
class IssueRepository extends BaseRepository
{
    /**
     * @return string
     */
    protected function getModel()
    {
        return Issue::class;
    }
}
