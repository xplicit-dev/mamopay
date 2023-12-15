<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents MamoPay Payment Link.
 */
class PaymentLink
{
    use PropertySetterTrait;

    /** @var string The identifier the payment link */
    public string $id;

    /** @var string The title of the payment link */
    public string $title;

    /** @var string Payment description. This will appear on the payment checkout page. */
    public string $description;

    /** @var int The number of times a payment link can be used. The capacity will be ignored when the subscription params exist. */
    public int $capacity;

    /** @var bool Indicates if the payment link is active */
    public bool $active;

    /** @var string The URL which the customer will be redirected to after a successful payment. */
    public string $return_url;

    /** @var string The URL which the customer will be redirected to after a failure payment. */
    public string $failure_return_url;

    /** @var float Processing fee percentage */
    public float $processing_fee_percentage;

    /** @var float Amount for the payment link */
    public float $amount;

    /** @var string Default: AED. Currency for the payment link amount */
    public string $amount_currency;

    /** @var bool Generate widget payment link. */
    public bool $is_widget;

    /** @var bool Enables the ability for customers to buy now and pay later. */
    public bool $enable_tabby;

    /** @var bool Enables the ability for customers to add a message during the checkout process. */
    public bool $enable_message;

    /** @var bool Enables the tips option. This will be displayed on the first screen. */
    public bool $enable_tips;

    /** @var string Allows the merchant to enable the option to store card details to be used later on for Merchant Initiated Transactions. Default: off */
    public string $save_card;

    /** @var bool Enables adding customer details such as the name, email, and phone number. This screen will be displayed before the payment details screen. */
    public bool $enable_customer_details;

    /** @var bool Enables adding customer details such as the name, email, and phone number. This screen will be displayed before the payment details screen. */
    public bool $payment_url;

    /** @var bool When enabled, customers can specify the number of items they intend to purchase. This quantity will serve as a multiplier for the base amount. */
    public bool $enable_quantity;

    /** @var bool Adds the ability to verify a payment through a QR code. */
    public bool $enable_qr_code;

    /** @var bool Enables the sending of customer receipts. */
    public bool $send_customer_receipt;

    /** @var array An array of accepted payment methods, card always apart of default option. Example: ['card', 'wallet'] */
    public array $payment_methods;

    /** @var object Setting the rule for payment link */
    public object $rules;

    /** @var Subscription|null Subscription information for the payment link (if applicable) */
    public ?Subscription $subscription;

    /** @var string The first name of the customer which will pre-populate in the card info step. */
    public string $first_name;

    /** @var string The last name of the customer which will pre-populate in the card info step. */
    public string $last_name;

    /** @var string The email of the customer which will pre-populate in the card info step. */
    public string $email;

    /** @var object The external ID of your choice to associate with payments captured by this payment link. */
    public object $custom_data;
}
