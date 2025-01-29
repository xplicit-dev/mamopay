<?php

namespace MamoPay\Api;

use MamoPay\Api\Objects\RecipientInfo;

/**
 * Recipient API
 */
class Recipient
{
    private HttpClient $httpClient;
    private string $endpoint = '/accounts/recipients';

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
     * @param RecipientInfo $recipientInfo
     * @param array $params Additional parameters like email, name, eid_number, bank, etc.
     * @return RecipientInfo
     */
    public function update(string $recipientIdentifier, RecipientInfo $recipientInfo, array $params = [])
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
}
