<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents MamoPay Subscriber Customer
 */
class Customer
{
    use PropertySetterTrait;

    /** @var string customer information */
    public string $id;

    /** @var string Customer's name */
    public string $name;


}
