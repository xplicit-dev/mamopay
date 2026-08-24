<?php

namespace MamoPay\Api;

/**
 * Invoice API
 */
class Invoice
{
    private HttpClient $httpClient;

    private $endpoint = '/invoices';

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Create Invoice
     * API to create and send an invoice to a customer via email.
     *
     * @param float $amount The invoice amount to be charged to the customer
     * @param string $email The customer's email address where the invoice will be sent
     * @param string $amount_currency Currency code for the invoice amount: AED, USD, EUR, GBP or SAR. Default AED.
     * @param string $description Optional description of the invoice
     * @param string $customer_type Optional customer type
     * @param string $first_name Optional first name of the customer
     * @param string $last_name Optional last name of the customer
     * @param string $phone_number Optional phone number of the customer
     * @param bool|null $vat_enabled Optional whether VAT is enabled on this invoice
     * @param array $params Additional documented parameters: additional_heading, additional_details,
     *                      additional_cc_emails, include_external_id, external_id, processing_fee_percentage,
     *                      processing_fee_amount
     * @return object
     */
    public function create(float $amount, string $email, string $amount_currency = 'AED', string $description = '', string $customer_type = '', string $first_name = '', string $last_name = '', string $phone_number = '', bool $vat_enabled = null, array $params = [])
    {
        $params = array_merge([
            'amount' => $amount,
            'email' => $email,
            'amount_currency' => $amount_currency,
            'description' => $description,
            'customer_type' => $customer_type,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone_number' => $phone_number,
            'vat_enabled' => $vat_enabled,
        ], $params);
        $params = array_filter($params, function ($value) {
            return $value !== null && $value !== '';
        });

        return $this->httpClient->sendRequest($this->endpoint, $params, HttpClient::METHOD_POST);
    }
}
