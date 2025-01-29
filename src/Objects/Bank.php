<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents Bank Details.
 */
class Bank
{
    use PropertySetterTrait;

    /** @var string IBAN */
    public string $iban;

    /** @var string Account number */
    public string $account_number;

    /** @var string Bank name */
    public string $name;

    /** @var string BIC code */
    public string $bic_code;

    /** @var string Bank address */
    public string $address;

    /** @var string Bank country */
    public string $country;
}
