<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents MamoPay Subscription.
 */
class Subscriber
{
    use PropertySetterTrait;

    /** @var string Unique identifier of subscription */
    public string $id;

    /** @var string Status of subscription */
    public string $status;

    /** @var ?Customer|null Customer information */
    public ?Customer $customer;

    /** @var int Number of payments */
    public int $number_of_payments;

    /** @var string Total paid for subscription */
    public string $total_paid;

    /** @var string The date for the next payment to be paid */
    public string $next_payment_date;
}
