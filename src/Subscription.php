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
     * Create a subscription object that defines a billing schedule (frequency, interval, start/end date, etc).
     * Note: this only creates the subscription object; attach the returned subscription identifier to a payment
     * link using the `subscription_id` attribute on the Links create/update APIs.
     *
     * @param string $frequency One of: daily, weekly, monthly, annually, test (daily and test are sandbox-only)
     * @param int $frequency_interval Defines how often this subscription will run based on the frequency
     * @param string $end_date Optional. The last date this subscription could run on (YYYY-MM-DD)
     * @param int $payment_quantity Optional. Number of times this subscription will occur. Ignored if end_date is set
     * @param string $weekly_start_day Optional. Day of week the subscription starts on (weekly frequency only)
     * @param int $monthly_start_date Optional. Day of month the subscription starts on (monthly/annually frequency only)
     * @param array $params Additional parameters
     * @return object
     */
    public function create(string $frequency, int $frequency_interval, string $end_date = '', int $payment_quantity = null, string $weekly_start_day = '', int $monthly_start_date = null, array $params = [])
    {
        $params = array_merge([
            'frequency' => $frequency,
            'frequency_interval' => $frequency_interval,
            'end_date' => $end_date,
            'payment_quantity' => $payment_quantity,
            'weekly_start_day' => $weekly_start_day,
            'monthly_start_date' => $monthly_start_date,
        ], $params);
        $params = array_filter($params, function ($value) {
            return $value !== null && $value !== '';
        });

        return $this->httpClient->sendRequest($this->endpoint, $params, HttpClient::METHOD_POST);
    }

    /**
     * Change Customer Subscription
     * Upgrades a subscriber from their current subscription to a target subscription, without requiring the
     * customer to re-enter their card details. Prorates and charges the difference against the target plan's
     * payment link, then activates the target subscriber and unsubscribes the current one.
     *
     * @param string $subscriptionId The subscriber's current subscription identifier
     * @param string $subscriberId The subscriber identifier
     * @param string $target_subscription_id The subscription identifier to upgrade the subscriber onto
     * @param array $params Additional parameters
     * @return object
     */
    public function changeCustomerSubscription(string $subscriptionId, string $subscriberId, string $target_subscription_id, array $params = [])
    {
        $params = array_merge(['target_subscription_id' => $target_subscription_id], $params);

        return $this->httpClient->sendRequest(
            $this->endpoint . $subscriptionId . '/subscribers/' . $subscriberId . '/change_customer_subscription',
            $params,
            HttpClient::METHOD_PATCH
        );
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
