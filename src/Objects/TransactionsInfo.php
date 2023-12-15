<?php

namespace MamoPay\Api\Objects;

/**
 * This object represents MamoPay Transactions info.
 */
class TransactionsInfo
{
    /** @var array<TransactionInfo> */
    public array $data;

    /** @var Pagination */
    public Pagination $pagination_meta;
}
