<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents MamoPay Business Info.
 */
class BusinessInfo
{
    use PropertySetterTrait;

    /** @var string business name */
    public string $business_name;

    /** @var string Value of business_tag */
    public string $business_tag;

    /** @var string website */
    public string $website;
}
