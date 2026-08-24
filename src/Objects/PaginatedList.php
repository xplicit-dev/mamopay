<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents a paginated list envelope as returned by the list endpoints
 * that respond with `data` and `pagination_meta` (e.g. partner cards, partner card
 * transactions, expense receipts).
 */
class PaginatedList
{
    use PropertySetterTrait;

    /** @var array The page items */
    public array $data;

    /** @var Pagination Pagination metadata */
    public Pagination $pagination_meta;
}
