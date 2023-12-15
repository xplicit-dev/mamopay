<?php

namespace MamoPay\Api\Objects;

/**
 * Class Pagination
 */
class Pagination
{
    /** @var int Current page number */
    public int $page;

    /** @var int Number of items per page */
    public int $per_page;

    /** @var int Total number of pages */
    public int $total_pages;

    /** @var int Next page number */
    public int $next_page;

    /** @var int Previous page number */
    public int $prev_page;

    /** @var int Starting index of items on the current page */
    public int $from;

    /** @var int Ending index of items on the current page */
    public int $to;

    /** @var int Total count of items across all pages */
    public int $total_count;
}
