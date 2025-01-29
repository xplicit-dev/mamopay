<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents MamoPay Recipient Info.
 */
class RecipientInfo
{

    const RECIPIENT_TYPE_INDIVIDUAL = 'individual';
    const RECIPIENT_TYPE_BUSINESS = 'business';

    const RELATIONSHIP_BUSINESS_ASSOCIATE_PARTNER = 'business_associate_partner';
    const RELATIONSHIP_CUSTOMER = 'customer';
    const RELATIONSHIP_EMPLOYEE = 'employee';
    const RELATIONSHIP_BRANCH_REPRESENTATIVE_OFFICE = 'branch_representative_office';
    const RELATIONSHIP_SUBSIDIARY_COMPANY = 'subsidiary_company';
    const RELATIONSHIP_HOLDING_COMPANY = 'holding_company';
    const RELATIONSHIP_SUPPLIER = 'supplier';
    const RELATIONSHIP_CREDITOR = 'creditor';
    const RELATIONSHIP_DEBTOR = 'debtor';
    const RELATIONSHIP_FRANCHISEE_FRANCHISOR = 'franchisee_franchisor';
    const RELATIONSHIP_OTHERS = 'others';

    use PropertySetterTrait;

    /** @var string Recipient identifier */
    public string $identifier;

    /** @var string Recipient type (individual/business) */
    public string $recipient_type;

    /** @var string Recipient First name */
    public string $first_name;

    /** @var string Recipient Last name */
    public string $last_name;    

    /** @var string Recipient name */
    public string $name;

    /** @var string Recipient relationship */
    public string $relationship;

    /** @var string|null Recipient email */
    public ?string $email;

    /** @var string|null Recipient Emirates ID number */
    public ?string $eid_number;

    /** @var string Reason for payouts */
    public string $reason;

    /** @var Address Address details */
    public Address $address;

    /** @var Bank Bank details */
    public Bank $bank;

    /** @var string Timestamp when recipient was created */
    public string $created_at;

    /** @var object Additional recipient metadata */
    public object $recipient_meta;
}
