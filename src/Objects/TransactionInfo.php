<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents MamoPay Transaction Info.
 */
class TransactionInfo
{
    use PropertySetterTrait;

    /** @var string Status of the payment
     * confirmation_required | captured | refund_initiated | processing | failed | refunded
     */
    public string $status;

    /** @var string Payment ID */
    public string $id;

    /** @var float Amount of the payment */
    public float $amount;

    /** @var string Currency of the payment amount */
    public string $amount_currency;

    /** @var float Amount of the refund */
    public float $refund_amount;

    /** @var string Status of the refund */
    public string $refund_status;

    /** @var object Additional custom data */
    public object $custom_data;

    /** @var string Date when the payment was created */
    public string $created_date;

    /** @var string Subscription ID (set for recurring payments, null for one-time payments) */
    public ?string $subscription_id;

    /** @var string Next payment date (set for recurring payments, null for one-time payments) */
    public ?string $next_payment_date;

    /** @var float Amount of the settlement */
    public float $settlement_amount;

    /** @var string Currency of the settlement amount */
    public string $settlement_currency;

    /** @var string Date of the settlement */
    public string $settlement_date;

    /** @var ?CustomerDetails Customer details */
    public ?CustomerDetails $customer_details;

    /** @var ?PaymentMethod|object Payment method details */
    public ?PaymentMethod $payment_method;

    /** @var string Settlement fee in AED */
    public string $settlement_fee;

    /** @var string Settlement VAT in AED */
    public string $settlement_vat;

    /** @var string|null Payment link ID (nullable)*/
    public ?string $payment_link_id;

    /** @var string|null Payment link URL (nullable)*/
    public ?string $payment_link_url;

    /** @var string|null External ID (nullable) */
    public ?string $external_id;

    /** @var string|null Error code (nullable) */
    public ?string $error_code;

    /** @var string|null Error message (nullable) */
    public ?string $error_message;
}
