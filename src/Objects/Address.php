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

    /** @var string State */
    public string $state;

    /** @var string Country */
    public string $country;
}
