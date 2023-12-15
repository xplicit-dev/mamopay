<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents MamoPay Customer Info
 */
class CustomerDetails
{
    use PropertySetterTrait;

    /** @var string Customer's name */
    public string $name;

    /** @var string Customer's email */
    public string $email;

    /** @var string Customer's phone number */
    public string $phone_number;

    /** @var string Additional comments */
    public string $comment;
}
