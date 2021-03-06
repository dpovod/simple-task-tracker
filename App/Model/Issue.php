<?php
declare(strict_types=1);

namespace App\Model;

use App\Exception\Model\AttributeNotExistsException;
use App\Model\Base\BaseModel;

/**
 * Class Issue
 * @package App\Model
 */
class Issue extends BaseModel
{
    public const STATUS_NEW = 0;

    public const STATUS_CLARIFICATION_REQUIRED = 1;

    public const STATUS_IN_PROGRESS = 2;

    public const STATUS_REVIEW_REQUIRED = 3;

    public const STATUS_REVIEW_IN_PROGRESS = 4;

    public const STATUS_REWORK_REQUIRED = 5;

    public const STATUS_DONE = 6;

    public const STATUS_REJECTED = 7;

    public const STATUES_NAMES = [
        self::STATUS_NEW => 'New',
        self::STATUS_CLARIFICATION_REQUIRED => 'Clarification required',
        self::STATUS_IN_PROGRESS => 'In progress',
        self::STATUS_REVIEW_REQUIRED => 'Review required',
        self::STATUS_REVIEW_IN_PROGRESS => 'Review in progress',
        self::STATUS_REWORK_REQUIRED => 'Rework required',
        self::STATUS_DONE => 'Done',
        self::STATUS_REJECTED => 'Rejected',
    ];

    /** @var array */
    protected $schema = [
        'id' => self::FIELD_TYPE_INT,
        'author_id' => self::FIELD_TYPE_INT,
        'assigned_to_id' => self::FIELD_TYPE_INT,
        'title' => self::FIELD_TYPE_STRING,
        'description' => self::FIELD_TYPE_STRING,
        'status' => self::FIELD_TYPE_INT,
        'created_at' => self::FIELD_TYPE_DATETIME,
        'updated_at' => self::FIELD_TYPE_DATETIME,
    ];

    /** @var string */
    protected const TABLE = 'issues';

    /**
     * @param int $symbolsLimit
     * @return string
     * @throws AttributeNotExistsException
     */
    public function getShortDescription(int $symbolsLimit): string
    {
        $description = $this->get('description');
        $descriptionShort = substr($description, 0, $symbolsLimit);

        if (mb_strlen($description) > $symbolsLimit) {
            return $descriptionShort . '...';
        }

        return $descriptionShort;
    }

    /**
     * @return string
     * @throws AttributeNotExistsException
     */
    public function getLink(): string
    {
        return getenv('SITE_URI') . '/issues/show/' . $this->get('id');
    }

    /**
     * @return string
     * @throws AttributeNotExistsException
     */
    public function getEditLink(): string
    {
        return getenv('SITE_URI') . '/issues/edit/' . $this->get('id');
    }

    /**
     * @return string
     * @throws AttributeNotExistsException
     */
    public function getStatusName(): string
    {
        return self::STATUES_NAMES[$this->get('status')];
    }
}
