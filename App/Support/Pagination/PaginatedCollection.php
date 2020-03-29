<?php
declare(strict_types=1);

namespace App\Support\Pagination;

use App\Exception\Model\AttributeNotExistsException;
use App\Helpers\ConfigHelper;
use App\Model\Base\BaseModel;
use App\Repository\Base\BaseRepository;

class PaginatedCollection
{
    /** @var array */
    private $items = [];

    /** @var int */
    private $total_count = 0;

    /** @var int */
    private $page;

    /** @var int */
    private $on_page;

    /** @var int */
    private $pages_count;

    /** @var string */
    private $list_link;

    /** @var Pagination */
    private $pagination;

    /**
     * PaginatedCollection constructor.
     * @param array $items
     * @param int $totalCount
     * @param string $listLink
     * @param int $page
     * @param int $onPage
     */
    public function __construct(array $items, int $totalCount, string $listLink, int $page = 1, int $onPage = 10)
    {
        $this->items = $items;
        $this->total_count = $totalCount;
        $this->list_link = $listLink;
        $this->page = $page;
        $this->on_page = $onPage;
        $this->pages_count = (int)ceil($this->total_count / $this->on_page);
        $this->pagination = $this->generatePagination();
    }

    /**
     * @param string $modelClassName
     * @param int $page
     * @param int $onPage
     * @param string $listLink
     * @param array $wheres
     * @return static
     * @throws AttributeNotExistsException
     * @throws \ReflectionException
     * @todo: implement filtering
     */
    public static function makeFromModel(string $modelClassName, string $listLink, int $page, int $onPage, array $wheres = [])
    {
        $repository = self::getRepositoryForModel($modelClassName);

        $items = $repository->getListPaginated($onPage, $onPage * ($page - 1));
        $totalCount = $repository->getCount();

        return new static($items, $totalCount, $listLink, $page, $onPage);
    }

    /**
     * @return array
     */
    private function generatePaginationLinks(): array
    {
        return array_map(function ($pageNumber) {
            return new PaginationLink($pageNumber, "$this->list_link?page=$pageNumber");
        }, range(1, $this->pages_count));
    }

    /**
     * @return Pagination
     */
    private function generatePagination()
    {
        return new Pagination($this->page, $this->on_page, $this->pages_count, $this->generatePaginationLinks());
    }

    /**
     * @param string $modelClassName
     * @return BaseRepository
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     * @throws \ReflectionException
     * @todo: move somewhere!?
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

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->total_count;
    }

    /**
     * @return int
     */
    public function getOnPage(): int
    {
        return $this->on_page;
    }

    /**
     * @return mixed
     */
    public function getListLink()
    {
        return $this->list_link;
    }

    /**
     * @return Pagination
     */
    public function getPagination()
    {
        return $this->pagination;
    }
}
