<?php

namespace MamoPay\Api;

use MamoPay\Api\Objects\BusinessInfo;

class MamoClient
{
    public $httpClient;

    /**
     * MamoPay Client
     * @param string $apikey
     * @param bool $use_sandbox
     */
    public function __construct($apikey='', $use_sandbox = false)
    {
        $this->httpClient = new HttpClient($apikey, $use_sandbox);
    }

    /**
     * fetch business info
     *
     * @return BusinessInfo
     */
    public function me()
    {
        return $this->httpClient->sendRequest('/me');
    }

    /**
     * Fetch Account Balances
     * API to fetch the balances of the merchant's different Mamo accounts.
     *
     * @return object{
     *     wallets: array<object{
     *         id: string,
     *         currency: string,
     *         balance: float,
     *         type: string,
     *     }>,
     * }
     */
    public function mybalance()
    {
        return $this->httpClient->sendRequest('/finances');
    }

    /**
     * Payment Links
     *
     * @return Links
     */
    public function links()
    {
        return (new Links($this->httpClient));
    }

    /**
     * Transactions
     *
     * @return Transaction
     */
    public function transaction()
    {
        return (new Transaction($this->httpClient));
    }

    /**
     * Subscriptions
     *
     * @return Subscription
     */
    public function subscription()
    {
        return (new Subscription($this->httpClient));
    }

    /**
     * Payouts
     *
     * @return Payout
     */
    public function payout()
    {
        return (new Payout($this->httpClient));
    }

    /**
     * Webhooks
     *
     * @return Webhook
     */
    public function webhook()
    {
        return (new Webhook($this->httpClient));
    }

    /**
     * Recipients
     *
     * @return Recipient
     */
    public function recipient()
    {
        return (new Recipient($this->httpClient));
    }

    /**
     * Card
     *
     * @return Cards
     */
    public function card()
    {
        return (new Cards($this->httpClient));
    }

    /**
     * Invoices
     *
     * @return Invoice
     */
    public function invoice()
    {
        return (new Invoice($this->httpClient));
    }
}
