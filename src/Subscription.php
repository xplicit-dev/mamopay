<?php

namespace MamoPay\Api;

use MamoPay\Api\Objects\Subscriber;
use MamoPay\Api\Objects\SubscriptionPayment;

/**
 * Subscription
 * 
 */
class Subscription
{
    private HttpClient $httpClient;

    private $endpoint = '/subscriptions/';

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Fetches all subscribers of subscription.
     *
     * @param int|null $page
     * @param int|null $per_page
     * @param array $params
     * @return array<SubscriptionPayment>
     */
    public function all(string $subscriptionId)
    {
        return $this->httpClient->sendRequest($this->endpoint . $subscriptionId . '/subscribers');
    }

    /**
     * Fetches all subscription payments made against a Recurring Payment item.
     *
     * @param string $subscriptionId
     * @return array<Subscriber>
     */
    public function get(string $subscriptionId)
    {
        return $this->httpClient->sendRequest($this->endpoint . $subscriptionId . '/payments');
    }

    /**
     * Unsubscribe subscription

     * @param string $subscriptionId
     * @param string $subscriberId
     * @return bool
     */
    public function unSubscribe(string $subscriptionId, string $subscriberId)
    {
        return $this->httpClient->sendRequest($this->endpoint . $subscriptionId . '/subscribers/' . $subscriberId, [], HttpClient::METHOD_DELETE);
    }

    /**
     * Cancels an existing recurring payment. This is NOT to unsubscribe a customer from a recurring payment that they have subscribed to. This deletes a previously created subscription for a business.
     * @param string $subscriptionId
     * @return bool
     */
    public function cancelRecurring(string $subscriptionId)
    {
        return $this->httpClient->sendRequest($this->endpoint . $subscriptionId, [], HttpClient::METHOD_DELETE);
    }
}
