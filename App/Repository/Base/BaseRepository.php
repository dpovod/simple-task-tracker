<?php
declare(strict_types=1);

namespace App\Repository\Base;

use App\Model\Base\BaseModel;

/**
 * Class BaseRepository
 * @package App\Repository
 */
class BaseRepository
{
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
     * @throws \ReflectionException
     */
    protected function getTable()
    {
        $reflectionClass = new \ReflectionClass($this->getModel());

        return $reflectionClass->getConstant('TABLE');
    }

    /**
     * @var \PDO
     */
    protected $connection;

    /**
     * BaseRepository constructor.
     * @throws \PDOException
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->table = $this->getTable();
        $dbName = getenv('DB_NAME');
        $host = getenv('DB_HOST');
        $dsn = "mysql:dbname=$dbName;host=$host";
        $user = getenv('DB_USER');
        $password = getenv('DB_PASS');

        $this->connection = new \PDO($dsn, $user, $password);
    }

    /**
     * @param array $row
     * @return BaseModel
     * @throws \ReflectionException
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
     * @throws \ReflectionException
     */
    private function createModelInstances(array $rows)
    {
        $result = [];

        foreach ($rows as $row) {
            $result[] = $this->createModelInstance($row);
        }

        return $result;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws \ReflectionException
     */
    public function getList(int $limit, int $offset)
    {
        $query = "SELECT * FROM `{$this->table}` LIMIT :limit OFFSET :offset";
        $statement = $this->connection->prepare($query);
        $statement->bindValue('limit', $limit, \PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, \PDO::PARAM_INT);
        $statement->execute();
        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if (!$rows) {
            return [];
        }

        return $this->createModelInstances($rows);
    }
}
