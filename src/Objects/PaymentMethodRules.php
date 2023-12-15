<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents MamoPay Payment Method Rules.
 */
class PaymentMethodRules
{
    use PropertySetterTrait;

    /** @var string An array of one or more bins that are accepted for payment, Google Pay and Apple Pay will be disabled when this rule is set. Example ['424242'] */
    public array $allowed_bins;
}
