<?php

namespace MamoPay\Api\Objects;

use MamoPay\Api\Traits\PropertySetterTrait;

/**
 * This object represents the result of a UAE IBAN validation.
 * Returned by GET /iban/validate. Always 200 OK for a well-formed request -
 * an invalid IBAN is reflected by `valid: false` with an `errors` array.
 */
class IbanValidation
{
    use PropertySetterTrait;

    /** @var string The validated IBAN */
    public string $iban;

    /** @var bool Whether the IBAN is valid */
    public bool $valid;

    /**
     * Country code. Omitted when valid is false.
     *
     * @var string|null
     */
    public ?string $country_code = null;

    /**
     * Bank identifier code. Omitted when valid is false.
     *
     * @var string|null
     */
    public ?string $bic_code = null;

    /**
     * Bank name. Omitted when valid is false.
     *
     * @var string|null
     */
    public ?string $bank_name = null;

    /**
     * Validation errors: too_short, bad_chars, unknown_country_code, bad_length,
     * bad_bban_format, bad_check_digits. Included when valid is false.
     *
     * @var array<string>|null
     */
    public ?array $errors = null;
}
