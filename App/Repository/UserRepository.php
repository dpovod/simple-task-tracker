<?php
declare(strict_types=1);

namespace App\Repository;

use App\Model\User;
use App\Repository\Base\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends BaseRepository
{
    /**
     * @return string
     */
    protected function getModel()
    {
        return User::class;
    }
}
