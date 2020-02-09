<?php
declare(strict_types=1);

namespace App\Model;

use App\Model\Base\BaseModel;

/**
 * Class User
 * @package App\Model
 */
class User extends BaseModel
{
    public const STATUS_APPROVE_REQUIRED = 0;

    public const STATUS_ACTIVE = 1;

    public const STATUS_BANNED = 2;

    public const STATUS_DELETED = 3;

    /** @var array */
    protected $schema = [
        'id' => self::FIELD_TYPE_INT,
        'login' => self::FIELD_TYPE_STRING,
        'password' => self::FIELD_TYPE_STRING,
        'email' => self::FIELD_TYPE_STRING,
        'status' => self::FIELD_TYPE_INT,
        'permissions' => self::FIELD_TYPE_JSON,
        'registered_at' => self::FIELD_TYPE_DATETIME,
    ];

    /** @var string */
    protected const TABLE = 'users';
}
