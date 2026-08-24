<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents a MamoPay International Payout response.
 * Returned by POST /international_payouts.
 */
class InternationalPayout
{
    use PropertySetterTrait;

    /** @var string Unique identifier of the international payout */
    public string $id;

    /**
     * Current status of the payout.
     *
     * @var string
     */
    public string $status;

    /** @var string Recipient's bank account */
    public string $recipient_account;

    /** @var string Type of the recipient (individual or business) */
    public string $recipient_type;

    /** @var string Recipient's name */
    public string $name;

    /** @var string The reason / category for this payout */
    public string $reason;

    /** @var string Free-text description of what the payout is for */
    public string $description;

    /** @var string Amount deducted from the merchant's balance (AED) */
    public string $source_amount;

    /** @var string Amount received by the recipient, in the destination currency */
    public string $amount;

    /** @var string Destination currency */
    public string $currency;

    /** @var float Applied exchange rate */
    public float $exchange_rate;

    /** @var float Payout fee amount */
    public float $payout_fee_amount;

    /** @var float VAT applied on the payout fee */
    public float $payout_fee_vat_amount;

    /** @var float Total amount deducted from the merchant's balance including fees */
    public float $total_deduction_amount;

    /** @var object Recipient's bank details used for the payout */
    public object $bank;

    /** @var string The date the payout was created at */
    public string $created_at;
}
