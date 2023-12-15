<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents MamoPay Subscription.
 */
class Subscription
{
    use PropertySetterTrait;

    /** @var string Identifier for the subscription */
    public string $identifier;

    /** @var string Indicates how often the subscription repeats */
    public string $repeats_every;

    /** @var string Defines the interval that this subscription will be run on. */
    public string $frequency;

    /** @var int Defines how often this subscription will run. */
    public int $frequency_interval;

    /** @var string The first date this subscription will run on. */
    public string $start_date;

    /** @var string The last date this subscription could run on. */
    public string $end_date;

    /** @var int Number of times this subscription will occur. If end_date is defined, end_date takes precedence. */
    public int $payment_quantity;
}
