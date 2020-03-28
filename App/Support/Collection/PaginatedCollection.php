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

    private $on_page;

    private $list_link;

    private $pagination_links = [];

    /**
     * PaginatedCollection constructor.
     * @param array $items
     * @param int $totalCount
     * @param $listLink
     * @param int $onPage
     */
    public function __construct(array $items, int $totalCount, $listLink, int $onPage = 10)
    {
        $this->items = $items;
        $this->total_count = $totalCount;
        $this->list_link = $listLink;
        $this->on_page = $onPage;
        $this->pagination_links = $this->generatePaginationLinks();
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

        return new static($items, $totalCount, $listLink, $onPage);
    }

    /**
     * @return array
     */
    private function generatePaginationLinks(): array
    {
        $pagesCount = (int)ceil($this->total_count / $this->on_page);

        return array_map(function ($page) {
            return "$this->list_link?page=$page";
        }, range(1, $pagesCount));
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
     * @return array
     */
    public function getPaginationLinks(): array
    {
        return $this->pagination_links;
    }
}
