<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents a MamoPay Recipient Balance.
 * Returned by GET /recipients/{recipientIdentifier}/balances.
 */
class RecipientBalance
{
    use PropertySetterTrait;

    /** @var string Unique identifier of the recipient's ledger */
    public string $identifier;

    /** @var float Real-time balance of the recipient's ledger */
    public float $balance;

    /** @var string Currency of the balance */
    public string $currency;
}
