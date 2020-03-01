<?php
declare(strict_types=1);

namespace App\Repository\Base;

use App\Exception\Model\AttributeNotExistsException;
use App\Model\Base\BaseModel;
use PDO;
use PDOException;
use ReflectionException;

/**
 * Class BaseRepository
 * @package App\Repository
 */
class BaseRepository
{
    public const CONDITION_AND = 'AND';

    public const CONDITION_OR = 'OR';

    /** @var string */
    protected $table;

    /**
     * @return string
     */
    protected function getModel()
    {
        return BaseModel::class;
    }

    /**
     * @throws ReflectionException
     */
    protected function getTable()
    {
        $reflectionClass = new \ReflectionClass($this->getModel());

        return $reflectionClass->getConstant('TABLE');
    }

    /**
     * @var PDO
     */
    protected $connection;

    /**
     * BaseRepository constructor.
     * @throws PDOException
     * @throws ReflectionException
     */
    public function __construct()
    {
        $this->table = $this->getTable();
        $dbName = getenv('DB_NAME');
        $host = getenv('DB_HOST');
        $dsn = "mysql:dbname=$dbName;host=$host";
        $user = getenv('DB_USER');
        $password = getenv('DB_PASS');

        $this->connection = new PDO($dsn, $user, $password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param array $row
     * @return BaseModel
     * @throws ReflectionException
     */
    private function createModelInstance(array $row)
    {
        $reflectionClass = new \ReflectionClass($this->getModel());
        /** @var BaseModel $instance */
        $instance = $reflectionClass->newInstance($row);

        return $instance;
    }

    /**
     * @param array $rows
     * @return BaseModel[]
     * @throws ReflectionException
     * @throws AttributeNotExistsException
     */
    private function createModelInstances(array $rows)
    {
        $result = [];

        foreach ($rows as $row) {
            $model = $this->createModelInstance($row);

            if ($model->has('id')) {
                $result[$model->get('id')] = $model;
            } else {
                $result[] = $model;
            }

        }

        return $result;
    }

    /**
     * @return array
     * @throws ReflectionException
     * @throws AttributeNotExistsException
     */
    public function getList()
    {
        $query = "SELECT * FROM `{$this->table}`";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return !$rows ? [] : $this->createModelInstances($rows);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws ReflectionException
     * @throws AttributeNotExistsException
     */
    public function getListPaginated(int $limit, int $offset)
    {
        $query = "SELECT * FROM `{$this->table}` LIMIT :limit OFFSET :offset";
        $statement = $this->connection->prepare($query);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, PDO::PARAM_INT);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return !$rows ? [] : $this->createModelInstances($rows);
    }

    /**
     * @param BaseModel $model
     * @return bool
     */
    public function insert(BaseModel $model)
    {
        $columns = $model->getAttributesKeys();
        $placeholders = array_fill(0, count($columns), '?');
        $columnsString = implode(',', $columns);
        $placeholdersString = implode(',', $placeholders);
        $query = "INSERT INTO `{$this->table}` ($columnsString) VALUES ($placeholdersString)";
        $statement = $this->connection->prepare($query);

        return $statement->execute(array_values($model->getAttributes()));
    }

    /**
     * @param BaseModel $model
     * @return bool
     * @throws AttributeNotExistsException
     */
    public function save(BaseModel $model)
    {
        $id = $model->get('id');

        $touched = $model->getTouchedAttributes();

        if (!$touched) {
            return false;
        }

        $updates = [];

        foreach ($model->getTouchedKeys() as $columnName) {
            $updates[] = "`$columnName` = ?";
        }

        $query = "UPDATE `{$this->table}` SET " . implode(', ', $updates) . ' WHERE `id` = ?';

        $statement = $this->connection->prepare($query);
        $params = array_values($touched);
        $params[] = $id;

        return $statement->execute($params);
    }

    /**
     * @param array $wheres
     * @param string $condition
     * @return BaseModel[]|array
     * @throws ReflectionException
     * @throws AttributeNotExistsException
     */
    public function findWhere(array $wheres, string $condition = self::CONDITION_AND)
    {
        $wheresColumns = array_map(function ($column) {
            return " `$column` = ? ";
        }, array_keys($wheres));

        $whereClause = implode($condition, $wheresColumns);
        $query = "SELECT * FROM `{$this->table}` WHERE $whereClause";
        $statement = $this->connection->prepare($query);
        $statement->execute(array_values($wheres));
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return !$rows ? [] : $this->createModelInstances($rows);
    }

    /**
     * @param array $wheres
     * @param string $condition
     * @return BaseModel|null
     * @throws ReflectionException
     * @throws AttributeNotExistsException
     */
    public function findFirstWhere(array $wheres, string $condition = self::CONDITION_AND): ?BaseModel
    {
        $models = $this->findWhere($wheres, $condition);

        return $models ? array_shift($models) : null;
    }
}
