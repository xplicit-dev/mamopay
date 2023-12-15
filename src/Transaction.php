<?php

namespace MamoPay\Api;

use MamoPay\Api\Objects\TransactionInfo;
use MamoPay\Api\Objects\TransactionsInfo;

/**
 * Transaction
 * 
 */
class Transaction
{
    private HttpClient $httpClient;

    private $endpoint = '/charges/';

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Fetches all transactions for a given business
     *
     * @param int|null $page
     * @param int|null $per_page
     * @param array $params
     * @return TransactionsInfo
     */
    public function all(int $page = null, int $per_page = null, $params = [])
    {
        $params = array_merge(['page' => $page, 'per_page' => $per_page], $params);

        return $this->httpClient->sendRequest($this->endpoint, [], $this->httpClient::METHOD_GET, $params);
    }

    /**
     * Create Payment
     * API to initiate transactions by merchant.
     * Merchant Initiated Transactions (MIT) allows a business to use card details, that were stored during previous transactions, to charge their customers.
     * @param string $title
     * @param float $amount
     * @param string $returnUrl
     * @param array $params
     *
     * @return TransactionInfo
     */
    public function create(string $card_id, float $amount, string $currency = 'AED', $params = [])
    {
        $params = array_merge(['card_id' => $card_id, 'amount' => $amount, 'currency' => $currency], $params);

        return $this->httpClient->sendRequest($this->endpoint, $params, $this->httpClient::METHOD_POST);
    }

    /**
     * Fetch Transaction Info
     *
     * @param string $chargeId
     * @return TransactionInfo
     */
    public function get(string $chargeId)
    {
        return $this->httpClient->sendRequest($this->endpoint . $chargeId);
    }

    /**
     * Refund Payment
     *
     * @param string $chargeId
     * @param float $amount
     * @param array $params
     * @return object{
     *     refund_status: string,
     *     refund_amount: float
     * }
     */
    public function refund(string $chargeId, $amount, $params = [])
    {
        $params = array_merge(['amount' => $amount], $params);

        return $this->httpClient->sendRequest($this->endpoint . $chargeId . '/refunds', $params);
    }
}
