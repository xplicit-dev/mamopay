<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents Address Details.
 */
class Address
{
    use PropertySetterTrait;

    /** @var string Address line 1 */
    public string $address_line1;

    /** @var string Address line 2 */
    public string $address_line2;

    /** @var string City */
    public string $city;

    /** @var string State (ISO 3166-2, e.g. CA for California) */
    public string $state;

    /** @var string Province, if applicable */
    public ?string $province = null;

    /** @var string Zip / postal code */
    public ?string $zip = null;

    /** @var string Country (two letters ISO country code) */
    public string $country;
}
