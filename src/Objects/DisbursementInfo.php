<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents MamoPay Disbursement Info
 */
class DisbursementInfo
{
    use PropertySetterTrait;

    /** @var int Unique id from the disbursement */
    public int $id;

    /** @var string Unique identifier of the disbursement */
    public string $identifier;

    /** @var string Reason for the disbursement */
    public string $reason;

    /** @var string Recipient's name */
    public string $name;

    /** @var string Transfer amount formatted as a string */
    public string $amount_formatted;

    /** @var string Type of transfer */
    public string $method;

    /** @var string Recipient's bank account */
    public string $recipient;

    /** @var string The date the disbursement was created at */
    public string $created_at;

    /**
     * Current status of the disbursement.
     * Possible values: Processing, Processed, Failed
     *
     * @var string
     */
    public string $status;

}
