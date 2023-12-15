<?php

namespace MamoPay\Api\Objects;

/**
 * Class PaymentLinks
 */
class PaymentLinks
{
    /** @var array<PaymentLink> */
    public array $data;

    /** @var Pagination */
    public Pagination $pagination_meta;
}
