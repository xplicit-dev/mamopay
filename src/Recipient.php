<?php

namespace MamoPay\Api;

use MamoPay\Api\Objects\RecipientInfo;

/**
 * Recipient API
 */
class Recipient
{
    private HttpClient $httpClient;
    private string $endpoint = '/recipients';

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }


    /**
     * Fetching Recipients
     * Fetches all recipients for a given business.
     *
     * @return RecipientInfo
     */
    public function all(array $params = [])
    {
        return $this->httpClient->sendRequest($this->endpoint, $params);
    }

    /**
     * Create a new recipient
     *
     * @param RecipientInfo $recipientInfo
     * @param array $params Additional parameters like email, name, eid_number, bank, etc.
     * @return RecipientInfo
     */
    public function create(RecipientInfo $recipientInfo, array $params = [])
    {
        return $this->httpClient->sendRequest($this->endpoint, $recipientInfo, $this->httpClient::METHOD_POST);
    }

    /**
     * Fetching Recipient
     * Allows a user to fetch recipient details.
     *
     * @return RecipientInfo
     */
    public function get(string $recipientIdentifier, array $params = [])
    {
        return $this->httpClient->sendRequest($this->endpoint . "/$recipientIdentifier", $params);
    }


    /**
     * Update Recipient
     *
     * @param string $recipientIdentifier Recipient Identifier
     * @param RecipientInfo|array $recipientInfo Recipient info object or a raw params array of the fields to update
     * @param array $params Additional parameters like email, name, eid_number, bank, etc.
     * @return RecipientInfo
     */
    public function update(string $recipientIdentifier, $recipientInfo, array $params = [])
    {
        return $this->httpClient->sendRequest($this->endpoint . "/$recipientIdentifier", $recipientInfo, $this->httpClient::METHOD_PATCH);
    }


    /**
     * Delete Recipient
     * Allows a user to delete recipient.
     *
     * @param string $recipientIdentifier
     * @return object{
     *     success: bool,
     * }
     */
    public function delete(string $recipientIdentifier, array $params = [])
    {
        return $this->httpClient->sendRequest($this->endpoint . "/$recipientIdentifier", $params, $this->httpClient::METHOD_DELETE);
    }

    /**
     * Fetch Recipient Balance
     * Fetches the real-time balance of a recipient's ledger. Recipients accumulate balance from payouts_share
     * splits on payments, and the balance is deducted when a payout is issued to the recipient.
     *
     * @param string $recipientIdentifier Recipient Identifier
     * @return object
     */
    public function balance(string $recipientIdentifier)
    {
        return $this->httpClient->sendRequest('/recipients/' . $recipientIdentifier . '/balances');
    }

    /**
     * Validate IBAN
     * Validates a UAE IBAN and returns the bank it belongs to. Useful for verifying a recipient's bank details
     * before creating them. Always returns 200 OK for a well-formed request - an invalid IBAN is reflected by
     * `valid: false` in the response with an `errors` array, not by an error response.
     *
     * @param string $iban The IBAN to validate
     * @return object{
     *     iban: string,
     *     valid: bool,
     *     country_code?: string,
     *     bic_code?: string,
     *     bank_name?: string,
     *     errors?: array<string>
     * }
     */
    public function validateIban(string $iban)
    {
        return $this->httpClient->sendRequest('/iban/validate', [], $this->httpClient::METHOD_GET, ['iban' => $iban]);
    }
}
