<?php
declare(strict_types=1);

namespace App\Support\Collection;

use App\Exception\Model\AttributeNotExistsException;
use App\Helpers\ConfigHelper;
use App\Model\Base\BaseModel;
use App\Repository\Base\BaseRepository;

class PaginatedCollection
{
    private $items = [];

    private $total_count = 0;

    /**
     * PaginatedCollection constructor.
     * @param array $items
     * @param int $totalCount
     */
    public function __construct(array $items, int $totalCount)
    {
        $this->items = $items;
        $this->total_count = $totalCount;
    }

    /**
     * @todo: implement filtering
     * @param string $modelClassName
     * @param int $page
     * @param int $onPage
     * @param array $wheres
     * @return static
     * @throws AttributeNotExistsException
     * @throws \ReflectionException
     */
    public static function makeFromModel(string $modelClassName, int $page, int $onPage, array $wheres = [])
    {
        $repository = self::getRepositoryForModel($modelClassName);

        $items = $repository->getListPaginated($onPage, $onPage * ($page - 1));
        $totalCount = $repository->getCount();

        return new static($items, $totalCount);
    }

    /**
     * @todo: move somewhere!?
     * @param string $modelClassName
     * @return BaseRepository
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     * @throws \ReflectionException
     */
    private static function getRepositoryForModel(string $modelClassName): BaseRepository
    {
        $repositories = ConfigHelper::get('app.repositories');
        $modelReflectionClass = new \ReflectionClass($modelClassName);

        //@todo: extract method
        if ($modelReflectionClass->getParentClass()->getName() !== BaseModel::class) {
            throw new \UnexpectedValueException('Model should be extended from ' . BaseModel::class);
        }

        foreach ($repositories as $repositoryClassName) {
            $repositoryInstance = new $repositoryClassName;

            //@todo: extract method
            if (get_parent_class($repositoryInstance) !== BaseRepository::class) {
                throw new \UnexpectedValueException('Repository should be extended from ' . BaseRepository::class);
            }

            /** @var BaseRepository $repositoryInstance */
            if ($repositoryInstance->isModel($modelClassName)) {
                return $repositoryInstance;
            }
        }

        throw new \RuntimeException("Repository not found for model '$modelClassName'.");
    }
}
