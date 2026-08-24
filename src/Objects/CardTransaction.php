<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents a MamoPay Card Transaction.
 * Returned by the card transaction endpoints (GET /cards/transactions/{transactionId} and
 * GET /partner_cards/{identifier}/transactions/{transaction_identifier}).
 */
class CardTransaction
{
    use PropertySetterTrait;

    /** @var string Kind of transaction */
    public string $kind;

    /** @var string Formatted merchant name */
    public string $merchant_name_formatted;

    /** @var string Currency of the transaction amount */
    public string $amount_currency;

    /** @var string Currency of the billing amount */
    public string $billing_amount_currency;

    /**
     * Current status of the transaction.
     *
     * @var string
     */
    public string $status;

    /** @var string Last 4 digits of the card */
    public string $card_last4;

    /** @var string Transaction identifier */
    public string $id;

    /** @var string Merchant name */
    public string $merchant_name;

    /** @var float Transaction amount */
    public float $amount;

    /** @var float Billing amount */
    public float $billing_amount;

    /** @var string Card name */
    public string $card_name;

    /** @var string Card holder name */
    public string $card_holder_name;

    /** @var string The date the transaction was created at */
    public string $created_at;

    /** @var string The date the transaction was last updated at */
    public string $updated_at;

    /** @var object|null Associated expense details */
    public ?object $expense = null;
}
