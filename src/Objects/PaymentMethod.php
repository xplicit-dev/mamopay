<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents MamoPay Payment Method.
 */
class PaymentMethod
{
    use PropertySetterTrait;

    /** @var string Type of payment method (e.g., CREDIT MASTERCARD, CREDIT VISA, DEBIT MASTERCARD, etc.) */
    public string $type;

    /** @var string Cardholder's name */
    public string $card_holder_name;

    /** @var string Last 4 digits of the card */
    public string $card_last4;

    /** @var string Origin of the payment method */
    public string $origin;
}
