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
}
