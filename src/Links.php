<?php

namespace MamoPay\Api;

use MamoPay\Api\Objects\PaymentLink;
use MamoPay\Api\Objects\PaymentLinks;

/**
 * This class represents the mamopay links resource
 */
class Links
{
    private HttpClient $httpClient;
    private $endpoint = '/links/';

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * get all Payment Links
     *
     * @return PaymentLinks
     */
    public function all()
    {
        return $this->httpClient->sendRequest($this->endpoint);
    }

    /**
     * Create Payment Link
     *
     * @param string $title
     * @param float $amount
     * @param string $returnUrl
     * @param array $params
     *
     * @return PaymentLink
     */
    public function create(string $title, float $amount, string $returnUrl = '', array $params = [])
    {
        $params = array_merge(['title' => $title, 'amount' => $amount, 'return_url' => $returnUrl], $params);

        return $this->httpClient->sendRequest($this->endpoint, $params, $this->httpClient::METHOD_POST);
    }

    /**
     * get PaymentLink Info
     *
     * @param string $linkID
     * @return PaymentLink
     */
    public function get($linkID)
    {
        return $this->httpClient->sendRequest($this->endpoint . $linkID);
    }

    /**
     * Create Payment Link
     *
     * @param string $linkID
     * @param array $params
     *
     * @return PaymentLink
     */
    public function update($linkID, $params)
    {
        return $this->httpClient->sendRequest($this->endpoint . $linkID, $params, $this->httpClient::METHOD_PATCH);
    }

    /**
     * Delete Payment Link
     *
     * @param [type] $linkID
     * @return bool
     */
    public function delete($linkID)
    {
        return $this->httpClient->sendRequest($this->endpoint . $linkID, [], $this->httpClient::METHOD_DELETE);
    }
}
