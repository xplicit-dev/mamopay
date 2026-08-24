<?php

namespace MamoPay\Api;

use MamoPay\Api\Objects\Disbursement;
use MamoPay\Api\Objects\DisbursementInfo;

class Payout
{
    private HttpClient $httpClient;

    private $endpoint = '/payouts/';

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Fetch all Disbursement Info
     *
     * @param int|null $page
     * @param int|null $per_page
     * @return array<DisbursementInfo>
     */
    public function all(int $page = null, int $per_page = null)
    {
        return $this->httpClient->sendRequest($this->endpoint, [], HttpClient::METHOD_GET, ['page' => $page, 'per_page' => $per_page]);
    }

    /**
     * Allows the issuance of disbursement
     *
     * @param string $account_no Recipient's IBAN / account number. Ignored when $recipient_id is provided.
     * @param float $amount Amount to be paid
     * @param string $first_name Name of the business or first name of the individual. Ignored when $recipient_id is provided.
     * @param string $last_name Recipient's last name. Ignored when $recipient_id is provided.
     * @param string $reason Description of what the payment is for
     * @param string $transfer_method Type of transfer. Currently only bank account transfers are supported (BANK_ACCOUNT)
     * @param string $recipient_id Optional. Identifier of a saved recipient (from the Create Recipient API). When provided,
     *                             the recipient's saved bank details are used and raw bank-detail fields can be omitted.
     * @param array $params Additional parameters
     * @return DisbursementInfo
     */
    public function issue($account_no, $amount, $first_name, $last_name = '', $reason = '', $transfer_method = 'BANK_ACCOUNT', $recipient_id = null, array $params = [])
    {
        $disbursement = (new Disbursement())->set([
            'account' => $account_no,
            'amount' => $amount,
            'first_name_or_business_name' => $first_name,
            'last_name' => $last_name,
            'reason' => $reason,
            'transfer_method' => $transfer_method,
        ]);
        if ($recipient_id !== null) {
            $disbursement->recipient_id = $recipient_id;
        }
        if (!empty($params)) {
            $disbursement->set($params);
        }
        return $this->issueMultiple([$disbursement])[0];
    }

    /**
     * Allows the issuance of disbursements in bulk
     *
     * @param Disbursement[] $disbursements An array of DisbursementInfo objects.
     * @return array<DisbursementInfo>
     */
    public function issueMultiple(array $disbursements)
    {
        return $this->httpClient->sendRequest($this->endpoint, ['disbursements' => $disbursements], HttpClient::METHOD_POST);
    }

    /**
     * Create International Payout
     * Initiates an international payout to an existing recipient (created via the Create Recipient API).
     * The destination currency and corridor are determined by the recipient's bank details.
     * Exactly one of $source_amount or $destination_amount must be provided - not both.
     *
     * @param string $recipient_id The identifier of an existing recipient
     * @param string $reason The reason / category for this payout
     * @param string|null $source_amount The amount to deduct from the merchant's balance as a string, always in AED. Minimum 100 AED.
     * @param string|null $destination_amount The amount the recipient should receive as a string, in the recipient's destination currency.
     * @param string $description Optional free-text description of what the payout is for
     * @param string|null $idempotency_key Optional Idempotency-Key header value; when null one is generated automatically so retries are safe
     * @return object
     */
    public function createInternational(string $recipient_id, string $reason, string $source_amount = null, string $destination_amount = null, string $description = '', string $idempotency_key = null)
    {
        $params = array_filter([
            'recipient_id' => $recipient_id,
            'reason' => $reason,
            'source_amount' => $source_amount,
            'destination_amount' => $destination_amount,
            'description' => $description,
        ], function ($value) {
            return $value !== null && $value !== '';
        });

        if ($idempotency_key === null) {
            $idempotency_key = 'mamo-php-' . bin2hex(random_bytes(16));
        }

        return $this->httpClient->sendRequest(
            '/international_payouts',
            $params,
            HttpClient::METHOD_POST,
            [],
            ['Idempotency-Key: ' . $idempotency_key]
        );
    }

    /**
     * Fetch Disbursement Info
     *
     * @param string $subscriptionId
     * @return DisbursementInfo
     */
    public function get(string $disbursementId)
    {
        return $this->httpClient->sendRequest($this->endpoint . '/' . $disbursementId);
    }
}
