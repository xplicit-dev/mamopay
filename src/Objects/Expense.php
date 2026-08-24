<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents a MamoPay Expense.
 * Returned by the Update Expense endpoint (PATCH /expenses/{expenseId}).
 */
class Expense
{
    use PropertySetterTrait;

    /**
     * Status of the expense.
     * Possible values: incomplete, pending_review, ready, synced
     *
     * @var string
     */
    public string $status;

    /** @var string Description of the expense */
    public string $description;

    /** @var string Invoice number */
    public string $invoiceNumber;

    /** @var string Expense identifier */
    public string $id;

    /** @var string Expense category */
    public string $category;

    /** @var string Tax code */
    public string $taxCode;

    /** @var string Payment account */
    public string $paymentAccount;

    /** @var string Expense account */
    public string $expenseAccount;

    /** @var string Vendor */
    public string $vendor;

    /** @var bool Whether a receipt has been uploaded */
    public bool $receiptUploaded;

    /** @var string The date the expense was created at */
    public string $createdAt;

    /** @var string The date the expense was last updated at */
    public string $updatedAt;
}
