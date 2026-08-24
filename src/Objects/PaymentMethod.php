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

    /** @var string|null Identifier of the saved card used, if the customer paid with a saved card. */
    public ?string $card_id = null;

    /** @var string|null Card expiry month. */
    public ?string $card_expiry_month = null;

    /** @var string|null Card expiry year. */
    public ?string $card_expiry_year = null;
}
