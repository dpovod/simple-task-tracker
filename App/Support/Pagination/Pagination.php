<?php
declare(strict_types=1);

namespace App\Support\Pagination;

class Pagination
{
    /** @var int */
    private $page;

    /** @var int */
    private $on_page;

    /** @var int */
    private $pages_count;

    /** @var PaginationLink[] */
    private $pagination_links = [];

    /**
     * Pagination constructor.
     * @param int $page
     * @param int $onPage
     * @param int $pagesCount
     * @param array $paginationLinks
     */
    public function __construct(int $page, int $onPage, int $pagesCount, array $paginationLinks)
    {
        $this->page = $page;
        $this->on_page = $onPage;
        $this->pages_count = $pagesCount;
        $this->pagination_links = $paginationLinks;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getOnPage(): int
    {
        return $this->on_page;
    }

    /**
     * @return int
     */
    public function getPagesCount(): int
    {
        return $this->pages_count;
    }

    /**
     * @return PaginationLink[]
     */
    public function getPaginationLinks(): array
    {
        return $this->pagination_links;
    }
}
