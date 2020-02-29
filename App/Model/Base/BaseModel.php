<?php
declare(strict_types=1);

namespace App\Model\Base;

use App\Exception\Model\AttributeNotExistsException;
use App\Exception\Model\FieldTypeNotAllowedException;
use DateTimeImmutable;
use DateTimeZone;
use Exception;

/**
 * Class BaseModel
 * @package App\Model\Base
 */
class BaseModel
{
    protected const FIELD_TYPE_STRING = 'string';

    protected const FIELD_TYPE_INT = 'int';

    protected const FIELD_TYPE_BOOL = 'bool';

    protected const FIELD_TYPE_DATETIME = 'datetime';

    protected const FIELD_TYPE_JSON = 'json';

    /** @var array */
    private const ALLOWED_FIELDS_TYPES = [
        self::FIELD_TYPE_STRING,
        self::FIELD_TYPE_INT,
        self::FIELD_TYPE_BOOL,
        self::FIELD_TYPE_DATETIME,
        self::FIELD_TYPE_JSON,
    ];

    private const DEFAULT_FIELD_TYPE = self::FIELD_TYPE_STRING;

    /** @var array */
    protected $schema;

    /** @var string */
    protected const TABLE = 'forge';

    /** @var array */
    protected $attributes = [];

    /**
     * BaseModel constructor.
     * @param array $fields
     * @throws FieldTypeNotAllowedException
     */
    public function __construct(array $fields)
    {
        foreach ($fields as $field => $attribute) {
            $this->attributes[$field] = $this->castAttribute($field, $attribute);
        }
    }

    /**
     * @param string $attributeName
     * @param $attributeValue
     * @return mixed
     * @throws FieldTypeNotAllowedException
     * @throws Exception
     */
    private function castAttribute(string $attributeName, $attributeValue)
    {
        $fieldType = $this->schema[$attributeName] ?? self::DEFAULT_FIELD_TYPE;

        if (!$this->isAllowedFieldType($fieldType)) {
            throw new FieldTypeNotAllowedException(
                sprintf("Field type '%s' is not allowed for table '%s'.", $fieldType, self::TABLE)
            );
        }

        switch ($fieldType) {
            case self::FIELD_TYPE_INT:
                return (int)$attributeValue;
            case self::FIELD_TYPE_BOOL:
                return (bool)$attributeValue;
            case self::FIELD_TYPE_DATETIME:
                return new DateTimeImmutable($attributeValue, new DateTimeZone('UTC'));
            case self::FIELD_TYPE_JSON:
                return json_decode($attributeValue, true);
            default:
                return (string)$attributeValue;
        }
    }

    /**
     * @param string $type
     * @return bool
     */
    private function isAllowedFieldType(string $type): bool
    {
        return in_array($type, self::ALLOWED_FIELDS_TYPES, true);
    }

    /**
     * @return array
     */
    public function getAttributesKeys(): array
    {
        return array_keys($this->attributes);
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $attribute
     * @return mixed
     * @throws AttributeNotExistsException
     */
    public function get(string $attribute)
    {
        if (!array_key_exists($attribute, $this->attributes)) {
            throw new AttributeNotExistsException("Attribute '$attribute' not exists in model instance.");
        }

        return $this->attributes[$attribute];
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function has(string $attribute): bool
    {
        return array_key_exists($attribute, $this->attributes);
    }

    /**
     * @param string $attribute
     * @param $value
     * @throws FieldTypeNotAllowedException
     */
    public function set(string $attribute, $value)
    {
        $this->attributes[$attribute] = $this->castAttribute($attribute, $value);
    }
}
