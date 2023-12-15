<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents MamoPay Disbursement
 */
class Disbursement
{
    use PropertySetterTrait;

    /** @var string Name of the business or the first name of the individual */
    public string $first_name_or_business_name;

    /** @var string Recipient's last name */
    public string $last_name;

    /** @var string Recipient's IBAN */
    public string $account;

    /**
     * Type of transfer. Currently only bank account transfers are supported.
     * Default: BANK_ACCOUNT
     *
     * @var string
     */
    public string $transfer_method = 'BANK_ACCOUNT';

    /** @var string Description of what the payment is for */
    public string $reason;

    /** @var string Amount to be paid as a string. Only AED transfers supported */
    public string $amount;

}
