<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents MamoPay Subscription Payment.
 */
class SubscriptionPayment
{
    use PropertySetterTrait;

    /** @var float The amount paid by the customer */
    public float $amount;

    /** @var string The name of the business (recipient) the payment was made for */
    public string $business_name;

    /** @var string The email address of the customer making the payment */
    public string $customer_email;

    /** @var string Customer's full name */
    public string $customer_name;

    /** @var string Customer's first name */
    public string $customer_first_name;

    /** @var string Customer's last name */
    public string $customer_last_name;

    /** @var string The currency the payment was made in (AED, USD, EUR) */
    public string $currency;

    /** @var string Customer's phone number including the country code */
    public string $customer_phone;

    /** @var string Unique identifier for a payment */
    public string $identifier;

    /** @var string The reason behind the payment */
    public string $payment_purpose;

    /** @var string The Recurring Payment URL that the subscription was created on */
    public string $payment_url;

    /**
     * The status of the payment.
     * - 'confirmation_required'
     * - 'captured'
     * - 'refund_initiated'
     * - 'processing'
     * - 'failed'
     * - 'refunded'
     *
     * @var string
     */
    public string $status;

    /** @var string The date the payment was made on */
    public string $created_at;
}
