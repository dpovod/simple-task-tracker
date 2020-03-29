<?php
declare(strict_types=1);

namespace App\Support\Pagination;

class PaginationLink
{
    /** @var int */
    private $page_number;

    /** @var string */
    private $link;

    /**
     * PaginationLink constructor.
     * @param int $pageNumber
     * @param string $link
     */
    public function __construct(int $pageNumber, string $link)
    {
        $this->page_number = $pageNumber;
        $this->link = $link;
    }

    /**
     * @return int
     */
    public function getPageNumber(): int
    {
        return $this->page_number;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }
}
