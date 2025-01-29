<?php

namespace MamoPay\Api\Objects;

/**
 * This object represents MamoPay Business Info.
 */
class CustomerDetails
{
    /** @var string Customer's name */
    public string $name;

    /** @var string Customer's email */
    public string $email;

    /** @var string Customer's phone number */
    public string $phone_number;

    /** @var string Additional comment from the customer */
    public string $comment;
}
